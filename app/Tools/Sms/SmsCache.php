<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 15:35
 */

namespace App\Tools\Sms;


use App\Tool\Sms\Drivers\SmsRedisCache;
use App\Tools\Sms\Drivers\SmsDbCache;

abstract class SmsCache
{
    abstract public function rememberCode($scene,$phone,$code,$expire);

    abstract public function checkCode($scene,$phone,$code);

    abstract public function canSendVerifyCode($scene,$phone);

    abstract public function removeCode($scene,$phone);

    public static function driver($name = 'redis')
    {
        switch ($name){
            case 'redis':
                return new SmsRedisCache();
            case 'db':
                return new SmsDbCache();
                break;
        }
    }
}