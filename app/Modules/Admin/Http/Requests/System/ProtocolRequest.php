<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Models\System\Protocol;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ProtocolRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Protocol::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'protocol_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',protocol_name' . $this->validate_id
            ],
            'protocol_type' => [
                'required',
            ],
            'protocol_content' => [
                'required',
            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'protocol_name.required' => '请输入版本名称！',
            'protocol_type.required'   => '请输入版本号！',
        ];
    }
}
