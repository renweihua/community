<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Models\System\Version;
use App\Modules\Admin\Http\Requests\BaseRequest;

class VersionRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Version::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'version_name'    => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',version_name' . $this->validate_id,
            ],
            'version_number'  => [
                'required',
                'unique:' . $this->instance->getTable() . ',version_number' . $this->validate_id,
            ],
            'publish_date' => [
                'date_format:Y-m-d H:i:s'
            ],
            'version_content' => [
                'required',
            ],
            'version_sort'    => [
                'min:0',
            ],
        ];
    }

    public function messages()
    {
        return [
            'version_name.required'    => '请输入版本名称！',
            'version_name.unique'      => '版本名称已存在，请更换！',
            'version_number.required'  => '请输入版本号！',
            'version_number.unique'    => '版本号已存在，请更换！',
            'publish_date.date_format'  => '请选择有效的发布时间！',
            'version_content.required' => '请输入版本内容！',
        ];
    }
}
