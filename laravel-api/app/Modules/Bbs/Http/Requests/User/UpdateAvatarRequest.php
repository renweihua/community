<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class UpdateAvatarRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_avatar' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_avatar.required' => '请上传头像！',
        ];
    }
}
