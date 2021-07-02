<?php

namespace App\Modules\Bbs\Http\Requests;

class UserIdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => '请指定会员！',
            'user_id.gt' => '请指定有效会员！',
        ];
    }
}
