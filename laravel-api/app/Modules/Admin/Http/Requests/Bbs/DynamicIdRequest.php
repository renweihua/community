<?php

namespace App\Modules\Admin\Http\Requests\Bbs;

use App\Modules\Admin\Http\Requests\BaseRequest;

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
            'dynamic_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'dynamic_id.required' => '请指定动态！',
        ];
    }
}
