<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Models\System\Config;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ConfigRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Config::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'config_title' => [
                'required',
                'max:256',
            ],
            'config_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',config_name' . $this->validate_id
            ]
        ];
    }

    public function messages()
    {
        return [
            'config_title.required' => '请输入配置标题！',
            'config_name.required'   => '请输入配置标识！',
        ];
    }
}
