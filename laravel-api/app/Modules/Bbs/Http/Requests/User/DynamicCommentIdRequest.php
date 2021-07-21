<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class DynamicCommentIdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_id' => 'required|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'comment_id.required' => '请指定评论！',
            'comment_id.gt' => '请指定有效的评论！',
        ];
    }
}
