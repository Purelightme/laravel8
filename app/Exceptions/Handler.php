<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * 表单验证失败返回
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $errmsg = '';
        foreach ($exception->errors() as $index => $error){
            if ($index == 0){
                $errmsg = $error[0];
            }
        }
        return response()->json([
            'errcode' => 2001,
            'errmsg'  => $errmsg,
            'data'    => $exception->errors()
        ],200);
    }

    /**
     * 认证失败返回
     *
     * @param Request $request
     * @param AuthenticationException $e
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request,AuthenticationException $e)
    {
        return response()->json([
            'errcode' => 1000,
            'errmsg'  => '请先登录',
            'data'    => [
                'url' => $request->getPathInfo()
            ]
        ],200);
    }
}
