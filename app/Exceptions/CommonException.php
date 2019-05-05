<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 15:52
 */

namespace App\Exceptions;


class CommonException extends \Exception
{
    public function render()
    {
        if (app()->environment('production'))
            return $this->renderProd();
        else
            return [
                'errcode' => $this->code,
                'errmsg' => $this->message,
                'data' => $this->getTrace(),
            ];
    }

    public function renderProd()
    {
        return [
            'errcode' => $this->code,
            'errmsg' => $this->message,
            'data' => [],
        ];
    }
}