<?php

namespace Modules\User\Http\Requests\User;

use App\Models\SmsCache;
use App\Rules\Phone;
use App\Rules\VerifyCode;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'unique:users',
                new Phone()
            ],
            'password' => 'required|min:6|confirmed',
            'code' => [
                'required',
                new VerifyCode(SmsCache::SCENE_REGISTER)
            ]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
