<?php

namespace App\Modules\Bbs\Http\Requests;

class DynamicIdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dynamic_id' => 'required|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'dynamic_id.required' => '请指定动态！',
            'dynamic_id.gt' => '请指定有效的动态！',
        ];
    }
}
