<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 17:08
 */

namespace App\Exceptions;


use Throwable;

class UploadException extends CommonException
{
    const EXCEPTION_MAP = [
        self::EXCEPTION_SIZE_TOO_BIG     => '文件过大，请适当压缩再上传',
        self::EXCEPTION_TYPE_NOT_SUPPORT => '该文件类型不支持',
    ];

    const EXCEPTION_SIZE_TOO_BIG     = 4000;
    const EXCEPTION_TYPE_NOT_SUPPORT = 4001;


    public function __construct(int $code = 0, string $message = "", Throwable $previous = null)
    {
        if (isset(self::EXCEPTION_MAP[$code]))
            $message = !empty($message) ? $message : self::EXCEPTION_MAP[$code];
        parent::__construct($message, $code, $previous);
    }
}