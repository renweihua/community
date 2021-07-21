<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class DynamicCommentRequest extends BaseRequest
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
            'content' => 'required_if:content_type,html',
            'markdown' => 'required_if:content_type,markdown',
        ];
    }

    public function messages()
    {
        return [
            'dynamic_id.required' => '请指定动态！',
            'dynamic_id.gt' => '请指定有效的动态！',
            'content.required' => '请输入评论内容！',
        ];
    }
}
