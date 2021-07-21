<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Models\User\User;
use App\Modules\Bbs\Http\Requests\BaseRequest;

class ChangeEmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_email' => [
                'required',
                'email',
                'unique:' . User::getInstance()->getTable() . ',user_email' . $this->validate_id,
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_email.required' => '请输入邮箱！',
            'user_email.email'    => '请输入有效邮箱！',
            'user_email.unique'   => '邮箱已被占用！',
        ];
    }
}
