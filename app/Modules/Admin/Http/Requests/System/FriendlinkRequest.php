<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Models\System\Friendlink;
use App\Modules\Admin\Http\Requests\BaseRequest;

class FriendlinkRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Friendlink::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'link_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',link_name' . $this->validate_id
            ],
            'link_url' => [
                'url',
            ],
            'link_cover' => [
                'required',
            ],
            'link_sort' => [
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
            'link_name.required' => '请输入友链名称！',
            'link_cover.required'   => '请上传友链图标！',
        ];
    }
}
