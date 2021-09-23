<?php

namespace App\Request\Bbs;

use App\Request\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'user_name' => 'required',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => '账户为必填项！',
            'password.required' => '密码为必填项！',
        ];
    }
}
