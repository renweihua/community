<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nick_name' => 'required|max:10',
            'user_sex' => 'required',
            'user_avatar' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nick_name.required' => '请输入昵称！',
            'nick_name.max' => '昵称不可超过10个字符！',
            'user_sex.required' => '请选择性别！',
            'user_avatar.required' => '请设置头像！',
        ];
    }
}
