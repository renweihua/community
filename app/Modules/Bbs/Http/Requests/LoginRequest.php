<?php

namespace App\Modules\Bbs\Http\Requests;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => '账户为必填项！',
            'password.required' => '密码为必填项！',
        ];
    }
}
