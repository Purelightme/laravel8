<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 15:38
 */

namespace App\Tool\Sms\Drivers;


use App\Tools\Sms\SmsCache;
use Illuminate\Support\Facades\Redis;

class SmsRedisCache extends SmsCache
{

    public function rememberCode($scene, $phone, $code, $expires)
    {
        $key = $this->buildKey($scene,$phone);
        Redis::setex($key,$expires,$code);
    }

    public function checkCode($scene, $phone, $code)
    {
        $cachedCode = Redis::get($this->buildKey($scene,$phone));
        if (!$cachedCode)
            return false;
        return $cachedCode == $code;
    }

    public function canSendVerifyCode($scene, $phone)
    {
        $ttl = Redis::ttl($phone);
        if ($ttl == -2)
            return true;
        if (config('sms.expires') - 60 > $ttl)
            return true;
        return false;
    }

    public function removeCode($scene, $phone)
    {
        Redis::del($this->buildKey($scene,$phone));
    }

    public function buildKey($scene,$phone)
    {
        return $scene . '#'. $phone;
    }
}