<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 17:59
 */

namespace App\Tools\Socialite;


use GuzzleHttp\Client;

class Socialite
{
    private static $driver;

    const DRIVER_WX = 'weixin';
    const DRIVER_QQ = 'qq';

    public static function driver($driver)
    {
        self::$driver = $driver;
        return new self();
    }

    /**
     * 获取社交账号基本信息,返回数据包含：nickname,headimgurl
     *
     * @param $openid
     * @param $accessToken
     * @return mixed
     */
    public static function getSocialiteOrigin($openid,$accessToken)
    {
        $client = new Client();
        switch (self::$driver){
            case self::DRIVER_WX:
                $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openid;
                break;
            case self::DRIVER_QQ:
                $url = 'https://graph.qq.com/user/get_user_info?access_token='.$accessToken.'&oauth_consumer_key='
                    .config('gcl.qq_app_id').'&openid='.$openid;
                break;
        }
        $response = $client->get($url);
        $userInfo = json_decode((string) $response->getBody(),true);
        if (self::$driver == self::DRIVER_QQ){
            $userInfo['headimgurl'] = $userInfo['figureurl_qq_2'];
        }
        return $userInfo;
    }
}