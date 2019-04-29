<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 15:50
 */

namespace App\Tools\Sms;


use App\Exceptions\SmsException;
use App\Tools\Response\ResponseTool;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsTool
{
    public static function init()
    {
        return new EasySms(config('sms.sms'));
    }

    /**
     * 发送简单短信
     * 无需验证，无需计时
     * @param $phone
     * @param array $params
     * @throws SmsException
     */
    public static function sendSimpleSms($phone, array $params)
    {
        self::verifyPhone($phone);
        $sms = self::init();
        if (config('sms.turn') == 'on') {
            try {
                $sms->send($phone, $params);
                $res = [];
            } catch (InvalidArgumentException $e) {
                throw new SmsException(SmsException::EXCEPTION_PARAMS_INVALID);
            } catch (NoGatewayAvailableException $e) {
                throw new SmsException(SmsException::EXCEPTION_NO_GATEWAY);
            }
        }else{
            if (app()->environment('local')){
                if (isset($params['data']['code']))
                    $code = ['code' => $params['data']['code']];
                $res = ResponseTool::buildSuccess($code ?? []);
            }else{
                $res = ResponseTool::buildSuccess();
            }
        }
        return $res;
    }

    /**
     * 发送验证码短信
     * @param $phone
     * @param array $params
     * @param $expires
     * @param string $scene
     * @throws SmsException
     */
    public static function sendVerifySms($phone, array $params, $expires, $scene = '')
    {
        if (!$scene)
            $scene = Str::random(6);
        if (!SmsCache::driver(config('sms.cache.driver'))->canSendVerifyCode($scene,$phone))
            throw new SmsException(SmsException::EXCEPTION_MUST_WAIT);
        $res = self::sendSimpleSms($phone,$params);
        if ($res['errcode'] == 0){
            SmsCache::driver(config('sms.cache.driver'))->rememberCode($scene,$phone,$params['data']['code'],$expires);
        }
        return $res;
    }

    /**
     * 校验手机号
     * @param $phone
     * @throws SmsException
     */
    public static function verifyPhone($phone)
    {
        if (preg_match('/1\d{10}/', $phone) != 1)
            throw new SmsException(SmsException::EXCEPTION_PHONE_INVALID);
    }

    /**
     * 产生随机验证码
     * @return string
     */
    public static function generateRandomCode()
    {
        $size = config('sms.code.size');
        $code = '';
        for ($i = 0;$i < $size;$i++){
            $code .= rand(0,9);
        }
        return $code;
    }
}