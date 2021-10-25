<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Models\System\Banner;
use App\Modules\Admin\Http\Requests\BaseRequest;

class BannerRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Banner::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'banner_title' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',banner_title' . $this->validate_id
            ],
            'banner_cover' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'banner_title.required' => '请输入Banner标题！',
            'banner_title.unique' => 'Banner标题已存在！',
            'banner_cover.required'   => '请上传Banner封面！',
        ];
    }
}
