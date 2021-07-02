<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Models\Rabc\AdminRole;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminRoleRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = AdminRole::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',role_name' . $this->validate_id
            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => '请输入角色名称！',
        ];
    }
}
