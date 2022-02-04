<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Models\User\User;
use App\Modules\Bbs\Http\Requests\BaseRequest;

class ChangeUserNameRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name' => [
                'required',
                'unique:' . User::getInstance()->getTable() . ',user_name' . $this->validate_id,
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => '请输入登录账户！',
            'user_name.unique'   => '登录账户已被占用！',
        ];
    }
}
