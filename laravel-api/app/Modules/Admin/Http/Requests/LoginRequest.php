<?php

namespace App\Modules\Admin\Http\Requests;

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
            'admin_name' => 'required',
            'password'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'admin_name.required' => '管理员账户为必填项！',
            'password.required'   => '管理员密码为必填项！',
        ];
    }
}
