<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Models\Rabc\Admin;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Admin::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'admin_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',admin_name' . $this->validate_id
            ],
            'admin_email' => [
                'required',
                'max:256',
                'email',
            ],
            'password' => [
//                'confirmed',
            ],
            'password_confirmation' => [

            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'admin_name.required' => '请输入管理员账户！',
            'admin_name.unique' => '管理员账户已存在！',
            'password.confirmed' => '密码确认不匹配！',
        ];
    }
}
