<?php

namespace App\Modules\Admin\Http\Requests;

use App\Models\UploadGroup;

class FileGroupRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = UploadGroup::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',group_name' . $this->validate_id
            ],
        ];
    }

    public function messages()
    {
        return [
            'group_name.required' => '请输入分组名称！',
            'group_name.max'      => '分组名称字数不可超过 256！',
            'group_name.unique'   => '分组名称已存在，请更换！',
        ];
    }
}
