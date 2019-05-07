<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\DbException;
use App\Exceptions\LogicException;
use App\Http\Resources\User\UserTokenResource;
use App\Models\User;
use App\Models\UserSocialite;
use App\Tools\Response\ResponseTool;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Requests\Socialite\UserSocialiteBindRequest;
use Modules\User\Http\Requests\Socialite\UserSocialiteLoginRequest;
use Modules\User\Http\Requests\Socialite\UserSocialiteRegisterRequest;
use Modules\User\Http\Requests\Socialite\UserSocialiteUnbindRequest;

class UserSocialiteController extends Controller
{
    /**
     * 使用第三方账号注册
     *
     * @param Request $request
     * @throws LogicException
     */
    public function register(UserSocialiteRegisterRequest $request)
    {
        $userInfo = UserSocialite::getSocialiteOrigin($request->type, $request->access_token,$request->openid);
        if (!isset($userInfo['nickname']))
            throw new LogicException(LogicException::EXCEPTION_OPENID_ERROR);
        $GLOBALS['user'] = null;
        DB::transaction(function ()use ($request,$userInfo){
            $user = User::NewUser($request->phone,$request->password);
            if (!$user->socialites()->create([
                'type' => $request->type,
                'openid' => $request->openid,
                'nickname' => $userInfo['nickname'],
                'avatar' => $userInfo['headimgurl']
            ]))
                throw new DbException(DbException::EXCEPTION_SAVE_FAIL);
            $GLOBALS['user'] = $user;
        });
        return ResponseTool::buildSuccess(new UserTokenResource($GLOBALS['user']));
    }

    /**
     * 第三方登录
     *
     * @return mixed
     * @throws LogicException
     */
    public function login(UserSocialiteLoginRequest $request)
    {
        $userInfo = UserSocialite::getSocialiteOrigin($request->type, $request->access_token,$request->openid);
        if (!isset($userInfo['nickname']))
            throw new LogicException(LogicException::EXCEPTION_OPENID_ERROR);
        if (UserSocialite::isBind($request->type, $request->openid)) {
            $socialite = UserSocialite::getSocialiteByOpenid($request->openid);
            return ResponseTool::buildSuccess(new UserTokenResource($socialite->user));
        } else {
            throw new LogicException(LogicException::EXCEPTION_NOT_BIND);
        }
    }

    /**
     * 绑定第三方账号【登录状态下】
     *
     * @param Request $request
     * @throws DbException
     * @throws LogicException
     */
    public function bind(UserSocialiteBindRequest $request)
    {
        $userInfo = UserSocialite::getSocialiteOrigin($request->type, $request->access_token,$request->openid);
        if (!isset($userInfo['nickname']))
            throw new LogicException(LogicException::EXCEPTION_OPENID_ERROR);
        $user = $request->user();
        $socialite = $user->socialites()->create([
            'type' => $request->type,
            'openid' => $request->openid,
            'nickname' => $userInfo['nickname'],
            'avatar' => $userInfo['headimgurl']
        ]);
        if (!$socialite)
            throw new DbException(DbException::EXCEPTION_SAVE_FAIL);
        return ResponseTool::buildSuccess();
    }

    /**
     * 解绑
     *
     * @param Request $request
     * @return mixed
     * @throws LogicException
     */
    public function unbind(UserSocialiteUnbindRequest $request)
    {
        $user = $request->user();
        UserSocialite::unbind($user->id,$request->type);
        return ResponseTool::buildSuccess();
    }
}
