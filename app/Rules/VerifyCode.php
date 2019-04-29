<?php

namespace App\Rules;

use App\Tools\Sms\SmsCache;
use Illuminate\Contracts\Validation\Rule;

class VerifyCode implements Rule
{
    /**
     * @var string  场景
     */
    private $scene;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($scene)
    {
        $this->scene = $scene;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $request = \request();
        $codes = explode('_',$attribute);
        if (count($codes) > 1)
            $phone = $request->input($codes[0].'_phone');
        else
            $phone = $request->input('phone');
        $isValid = SmsCache::driver(config('sms.cache.driver'))->checkCode($this->scene,$phone,$value);
        if ($isValid){
            SmsCache::driver(config('sms.cache.driver'))->removeCode($this->scene,$phone);
        }
        return $isValid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '验证码有误';
    }
}
