<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class MonthRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'month' => 'required|date:Y-m',
        ];
    }

    public function messages()
    {
        return [
            'month.required' => '请设置月份！',
            'month.date' => '请设置有效月份！',
        ];
    }
}
