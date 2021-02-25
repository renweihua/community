<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class ChangePasswordByEmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'password' => 'required|min:6|max:20|confirmed',
            'password_confirmation'   => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'code.required'   => '请输入验证码！',
            'password.required'   => '请设置登录密码！',
            'password.min'   => '登录密码长度最小6个字符！',
            'password.max'   => '登录密码长度最大20个字符！',
            'password.confirmed'   => '两次输入密码不一致！',
            'password_confirmation.required'   => '请输入确认密码！',
            'password_confirmation.same'   => '两次输入密码不一致！',
        ];
    }
}
