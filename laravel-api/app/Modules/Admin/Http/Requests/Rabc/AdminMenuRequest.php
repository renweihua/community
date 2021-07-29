<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Models\Rabc\AdminMenu;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminMenuRequest extends BaseRequest
{
    // public function setInstance()
    // {
    //     $this->instance = AdminMenu::getInstance();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu_name' => [
                'required',
                'max:256',
                // 'unique:' . $this->instance->getTable() . ',menu_name' . $this->validate_id
            ],
        ];
    }

    public function messages()
    {
        return [
            'menu_name.required' => '请输入菜单名称！',
            'menu_name.unique' => '菜单名称已存在！',
        ];
    }
}
