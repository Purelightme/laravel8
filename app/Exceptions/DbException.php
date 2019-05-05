<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/4/30
 * Time: 15:00
 */

namespace App\Exceptions;


use Throwable;

class DbException extends CommonException
{

    const EXCEPTION_MAP = [
        self::EXCEPTION_SAVE_FAIL => '数据保存失败',
    ];

    const EXCEPTION_SAVE_FAIL = '5000';

    public function __construct(int $code = 0, string $message = "", Throwable $previous = null)
    {
        if (isset(self::EXCEPTION_MAP[$code]))
            $message = !empty($message) ? $message : self::EXCEPTION_MAP[$code];
        parent::__construct($message, $code, $previous);
    }
}