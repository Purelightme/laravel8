<?php

namespace Modules\User\Http\Requests\Socialite;

use App\Models\UserSocialite;
use Illuminate\Foundation\Http\FormRequest;

class UserSocialiteLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:'.implode(',',array_keys(UserSocialite::TYPE_MAP)),
            'access_token' => 'required',
            'openid' => 'required',
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
