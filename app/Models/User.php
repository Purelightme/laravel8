<?php

namespace App\Models;

use App\Exceptions\DbException;
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

    /***********************模型关联区********************/

    public function scopeValid()
    {

    }

    /***********************自定义方法区*******************/

    /**
     * 添加用户
     *
     * @param $phone
     * @param $password
     * @throws DbException
     */
    public static function NewUser($phone,$password)
    {
        $order = self::create(['phone' => $phone,'password' => bcrypt($password)]);
        if (!$order)
            throw new DbException(DbException::EXCEPTION_SAVE_FAIL);
        return $order;
    }

    public static function getUserByPhone($phone)
    {
        $user = self::where('phone',$phone)->first();
        if (!$user)
            throw new \Exception();
        return $user;
    }

    /***********************模型修改区********************/

}
