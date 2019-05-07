<?php

namespace App\Models;

use App\Exceptions\LogicException;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class UserSocialite extends Model
{
    protected $guarded = [];

    const TYPE_MAP = [
        self::TYPE_QQ => 'QQ',
        self::TYPE_WX => '微信'
    ];
    const TYPE_QQ = 'qq';
    const TYPE_WX = 'wx';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function isBind($type,$openid)
    {
        $socialite = self::where([['type',$type],['openid',$openid]])->first();
        return $socialite && $socialite->user_id;
    }

    public static function getSocialiteByOpenid($openid)
    {
        return UserSocialite::where('openid', $openid)->first();
    }

    /**
     * 获取社交账号原始信息
     *
     * @param $type
     * @param $accessToken
     * @param $openid
     * @return mixed
     * @throws LogicException
     */
    public static function getSocialiteOrigin($type, $accessToken, $openid)
    {
        $client = new Client();
        switch ($type){
            case self::TYPE_WX:
                $response =  $client->get('https://api.weixin.qq.com/sns/userinfo',[
                    'query' => [
                        'access_token' => $accessToken,
                        'openid' => $openid
                    ]
                ]);
                break;
            case self::TYPE_QQ:
                $response = $client->get('https://graph.qq.com/user/get_user_info',[
                    'query' => [
                        'access_token' => $accessToken,
                        'oauth_consumer_key' => config('xyd.qq_app_id'),
                        'openid' => $openid
                    ]
                ]);
                break;
        }
        $userInfo = json_decode((string)$response->getBody(),true);
        if ($userInfo['res'] < 0)
            throw new LogicException(LogicException::EXCEPTION_OTHERS,$userInfo['msg']);
        if($type == self::TYPE_QQ){
            $userInfo['headimgurl'] = $userInfo['figureurl_qq_2'];
        }
        return $userInfo;
    }

    /**
     * 解除绑定
     *
     * @param $userId
     * @param $type
     * @throws LogicException
     */
    public static function unbind($userId, $type)
    {
        $socialite = self::where('user_id',$userId)->where('type',$type)->first();
        if (!$socialite)
            throw new LogicException(LogicException::EXCEPTION_NOT_BIND);
        $socialite->delete();
    }

}
