<?php

namespace App\Request\Bbs;

use App\Request\BaseRequest;

class EmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'user_email' => 'required|email',
        ];
    }

    public function messages() : array
    {
        return [
            'user_email.required' => '请输入邮箱！',
            'user_email.email' => '请输入有效的邮箱地址！',
        ];
    }
}
