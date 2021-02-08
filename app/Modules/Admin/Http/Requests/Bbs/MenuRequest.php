<?php

namespace App\Modules\Admin\Http\Requests\Bbs;

use App\Models\Bbs\Menu;
use App\Modules\Admin\Http\Requests\BaseRequest;

class MenuRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Menu::getInstance();
    }

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
                'unique:' . $this->instance->getTable() . ',menu_name' . $this->validate_id
            ],
        ];
    }

    public function messages()
    {
        return [
            'menu_name.required' => '请输入菜单名称！',
        ];
    }
}
