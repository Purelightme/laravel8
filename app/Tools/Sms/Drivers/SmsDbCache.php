<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 15:37
 */

namespace App\Tools\Sms\Drivers;

use App\Tools\Sms\SmsCache;
use Carbon\Carbon;
use App\Models\SmsCache as SmsCacheModel;

class SmsDbCache extends SmsCache
{

    public function rememberCode($scene, $phone, $code, $expire)
    {
        SmsCacheModel::create([
            'scene' => $scene,
            'phone' => $phone,
            'code' => $code,
            'expires_at' => Carbon::now()->addSeconds($expire)
        ]);
    }

    public function checkCode($scene, $phone, $code)
    {
        $cache = SmsCacheModel::getRecordBySceneAndPhone($scene,$phone);
        return $code == $cache->code;
    }

    public function canSendVerifyCode($scene, $phone)
    {
        $record = SmsCacheModel::getRecordBySceneAndPhone($scene,$phone);
        if (!$record)
            return true;
        $now = Carbon::now();
        $expiresAt = Carbon::parse($record->expires_at);
        if ($expiresAt->subSeconds(60)->gt($now))
            return true;
        return false;
    }

    public function removeCode($scene, $phone)
    {
        $record = SmsCacheModel::getRecordBySceneAndPhone($scene,$phone);
        $record->delete();
    }
}