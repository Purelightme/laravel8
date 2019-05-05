<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCache extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const SCENE_REGISTER = 'register';
    const SCENE_LOGIN = 'login';

    /**
     * 获取最近的短信记录
     *
     * @param $scene
     * @param $phone
     * @return mixed
     */
    public static function getRecordBySceneAndPhone($scene,$phone)
    {
        $record = self::where([
            ['scene',$scene],
            ['phone',$phone]
        ])->latest()->first();
        return $record;
    }
}
