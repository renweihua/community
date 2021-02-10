<?php

namespace App\Modules\Bbs\Http\Requests;

class RegisterRequest extends BaseRequest
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
            'password'   => 'required|confirmed',
            'password_confirmation'   => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => '请输入账户！',
            'password.required'   => '请设置登录密码！',
            'password.confirmed'   => '两次输入密码不一致！',
            'password_confirmation.required'   => '请输入确认密码！',
            'password_confirmation.same'   => '两次输入密码不一致！',
        ];
    }
}
