<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class SpecialUserRequest extends BaseRequest
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
            'user_id.required' => '请指定有效好友！',
            'user_id.gt' => '请指定有效会员！',
        ];
    }
}
