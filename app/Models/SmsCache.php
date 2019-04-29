<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCache extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function getRecordBySceneAndPhone($scene,$phone)
    {
        return self::where([
            ['scene',$scene],
            ['phone',$phone]
        ])->last();
    }
}
