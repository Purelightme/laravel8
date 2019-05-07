<?php

namespace App\Models;

use App\Exceptions\DbException;
use App\Exceptions\LogicException;
use App\Notifications\RegisterSuccessNotification;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];



    const TOKEN_NAME = 'laravel8';

    /***********************模型关联区********************/

    public function scopeValid()
    {

    }

    public function socialites()
    {
        return $this->hasMany(UserSocialite::class);
    }

    /***********************自定义方法区*******************/

    public function findForPassport($login)
    {
        return $this->orWhere('phone', $login)->first();
    }

    /**
     * 获取个人访问令牌,有效期1年
     *
     * @param self $user
     * @return array
     */
    public static function getToken(self $user)
    {
        $token = $user->createToken(self::TOKEN_NAME);
        return [
            'token' => $token->accessToken,
            'expires_at' => (string)$token->token->expires_at
        ];
    }

    /**
     * 获取访问令牌,可以自行设置有效期
     *
     * @param $phone
     * @param $password
     * @return mixed
     */
    public static function getOauthToken($phone,$password)
    {
        $http = new Client;
        $response = $http->post(config('app.url').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('laravel8.passport.client_id'),
                'client_secret' => config('laravel8.passport.client_secret'),
                'username' => $phone,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * 添加用户
     *
     * @param $phone
     * @param $password
     * @throws DbException
     * @return self
     */
    public static function NewUser($phone,$password)
    {
        $user = self::create(['phone' => $phone,'password' => bcrypt($password)]);
        if (!$user)
            throw new DbException(DbException::EXCEPTION_SAVE_FAIL);
        $user->notify(new RegisterSuccessNotification($user));
        return $user;
    }

    /**
     * 根据手机号获取用户
     * 
     * @param $phone
     * @param bool $throw
     * @return mixed
     * @throws LogicException
     */
    public static function getUserByPhone($phone, $throw = true)
    {
        $user = self::where('phone',$phone)->first();
        if (!$user && $throw)
            throw new LogicException(LogicException::EXCEPTION_USER_NOT_FOUND);
        return $user;
    }

    /**
     * 重置用户密码
     *
     * @param self $user
     * @param $password
     * @return self
     * @throws DbException
     */
    public static function resetUserPassword(self $user, $password)
    {
        $user->password = bcrypt($password);
        if (!$user->save())
            throw new DbException(DbException::EXCEPTION_SAVE_FAIL);
        return $user;
    }

    /***********************模型修改区********************/

}
