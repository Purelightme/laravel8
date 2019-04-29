<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 14:29
 */

namespace App\Tools\Response;


class ResponseTool
{
    public static function buildSuccess($data = [])
    {
        return [
            'errcode' => 0,
            'errmsg' => 'ok',
            'data' => $data,
        ];
    }

    public static function buildSuccessWithMsg($msg, $data = [])
    {
        return [
            'errcode' => 0,
            'errmsg' => $msg,
            'data' => $data,
        ];
    }

    public static function buildFail($errCode, $errMsg, $data = [])
    {
        return [
            'errcode' => $errCode,
            'errmsg' => $errMsg,
            'data' => $data
        ];
    }
}