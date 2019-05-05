<?php

namespace Modules\User\Http\Requests\User;

use App\Models\SmsCache;
use App\Rules\VerifyCode;
use Illuminate\Foundation\Http\FormRequest;

class FindPasswordByCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|exists:users',
            'code' => [
                'required',
                new VerifyCode(SmsCache::SCENE_FIND_PASSWORD)
            ],
            'password' => 'required|min:6|confirmed'
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
