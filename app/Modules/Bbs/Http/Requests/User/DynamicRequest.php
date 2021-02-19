<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class DynamicRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dynamic_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'dynamic_type.required' => '请设置发布类型！',
        ];
    }
}
