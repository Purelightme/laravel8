<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/4/30
 * Time: 17:18
 */

namespace App\Exceptions;


use Throwable;

class LogicException extends CommonException
{

    const EXCEPTION_MAP = [
        self::EXCEPTION_USER_NOT_FOUND => '用户未找到',
        self::EXCEPTION_PASSWORD_ERROR => '密码有误',
    ];

    const EXCEPTION_USER_NOT_FOUND = '6001';
    const EXCEPTION_PASSWORD_ERROR = '6002';

    public function __construct(int $code = 0, string $message = "", Throwable $previous = null)
    {
        if (isset(self::EXCEPTION_MAP[$code]))
            $message = !empty($message) ? $message : self::EXCEPTION_MAP[$code];
        parent::__construct($message, $code, $previous);
    }
}