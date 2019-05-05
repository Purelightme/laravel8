<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\LogicException;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserTokenResource;
use App\Models\SmsCache;
use App\Models\User;
use App\Tools\Response\ResponseTool;
use App\Tools\Sms\SmsTool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\Sms\AlreadyRegisteredRetrieveSmsRequest;
use Modules\User\Http\Requests\Sms\NotRegisteredRetrieveSmsRequest;
use Modules\User\Http\Requests\User\ChangePasswordRequest;
use Modules\User\Http\Requests\User\FindPasswordByCodeRequest;
use Modules\User\Http\Requests\User\LoginByCodeRequest;
use Modules\User\Http\Requests\User\LoginByPasswordRequest;
use Modules\User\Http\Requests\User\RegisterRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index(Request $request)
    {
        return ResponseTool::buildSuccess(new UserResource($request->user()));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
//        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     * @throws \App\Exceptions\DbException
     */
    public function store(RegisterRequest $request)
    {
        $user = User::NewUser($request->phone,$request->password);
        return $user;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
//        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
//        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 获取注册短信
     *
     * @param NotRegisteredRetrieveSmsRequest $request
     * @throws \App\Exceptions\SmsException
     */
    public function retrieve_register_code(NotRegisteredRetrieveSmsRequest $request)
    {
        $params = [
            'template' => config('sms.templates.register'),
            'data' => [
                'code' => SmsTool::generateRandomCode()
            ]
        ];
        $res = SmsTool::sendVerifySms($request->phone,$params,config('sms.expires'),SmsCache::SCENE_REGISTER);
        return $res;
    }

    /**
     * 获取登录短信
     * 
     * @param AlreadyRegisteredRetrieveSmsRequest $request
     * @return array
     * @throws \App\Exceptions\SmsException
     */
    public function retrieve_login_code(AlreadyRegisteredRetrieveSmsRequest $request)
    {
        $params = [
            'template' => config('sms.templates.login'),
            'data' => [
                'code' => SmsTool::generateRandomCode()
            ]
        ];
        $res = SmsTool::sendVerifySms($request->phone,$params,config('sms.expires'),SmsCache::SCENE_LOGIN);
        return $res;
    }

    /**
     * 获取找回密码短信
     *
     * @param AlreadyRegisteredRetrieveSmsRequest $request
     * @return array
     * @throws \App\Exceptions\SmsException
     */
    public function retrieve_find_password_code(AlreadyRegisteredRetrieveSmsRequest $request)
    {
        $params = [
            'template' => config('sms.templates.find_password'),
            'data' => [
                'code' => SmsTool::generateRandomCode()
            ]
        ];
        $res = SmsTool::sendVerifySms($request->phone,$params,config('sms.expires'),SmsCache::SCENE_FIND_PASSWORD);
        return $res;
    }

    /**
     * 短信登录
     *
     * @param LoginByCodeRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function login_by_code(LoginByCodeRequest $request)
    {
        $user = User::getUserByPhone($request->phone);
        return ResponseTool::buildSuccess(new UserTokenResource($user));
    }

    /**
     * 密码登录
     *
     * @param LoginByPasswordRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function login_by_password(LoginByPasswordRequest $request)
    {
        $user = User::getUserByPhone($request->phone);
        if (!Hash::check($request->password,$user->password))
            throw new LogicException(LogicException::EXCEPTION_PASSWORD_ERROR);
        return ResponseTool::buildSuccess(new UserTokenResource($user));
    }

    /**
     * 修改密码
     *
     * @param ChangePasswordRequest $request
     * @return array
     * @throws \App\Exceptions\DbException
     */
    public function change_password(ChangePasswordRequest $request)
    {
        $user = $request->user();
        User::resetUserPassword($user,$request->password);
        return ResponseTool::buildSuccess();
    }

    /**
     * 找回密码-使用短信
     *
     * @param FindPasswordByCodeRequest $request
     * @return array
     * @throws LogicException
     * @throws \App\Exceptions\DbException
     */
    public function find_password_by_code(FindPasswordByCodeRequest $request)
    {
        $user = User::getUserByPhone($request->phone);
        User::resetUserPassword($user,$request->password);
        return ResponseTool::buildSuccess();
    }
}
