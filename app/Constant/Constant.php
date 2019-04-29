<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/18
 * Time: 23:53
 */

namespace App\Constant;


class Constant
{
    /**
     * laravel-admin 通用switch开关参数
     */
    const SWITCH_STATE = [
        'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
        'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
    ];
}