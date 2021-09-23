<?php

namespace App\Request\Bbs;

use App\Request\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'register_type' => 'required|in:0,1,2',
            'user_name' => 'required|min:2',
            'password'   => 'required|confirmed',
            'password_confirmation'   => 'required|same:password',
        ];
    }

    public function messages() : array
    {
        return [
            'register_type.required' => '请设置注册方式！',
            'register_type.in' => '注册方式无效，请返回重试！',
            'user_name.required' => '请输入账户！',
            'user_name.min' => '账户至少两个字符！',
            'password.required'   => '请设置登录密码！',
            'password.confirmed'   => '两次输入密码不一致！',
            'password_confirmation.required'   => '请输入确认密码！',
            'password_confirmation.same'   => '两次输入密码不一致！',
        ];
    }
}
