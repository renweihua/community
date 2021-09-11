<?php

namespace App\Modules\Admin\Http\Requests\Bbs;

use App\Modules\Admin\Http\Requests\BaseRequest;

class DynamicCheckRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dynamic_id'    => 'required',
            'is_check'      => [
                'required',
                'in:1,2',
            ],
            'admin_remarks' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'dynamic_id.required'    => '请指定动态！',
            'is_check.required'      => '请选择审核状态！',
            'is_check.in'            => '请选择有效审核标识！',
            'admin_remarks.required' => '请输入审核备注！',
        ];
    }
}
