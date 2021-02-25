<?php

namespace App\Modules\Bbs\Http\Requests;

class TopicIdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topic_id' => 'required|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => '请指定荟吧！',
            'topic_id.gt' => '请指定有效的荟吧！',
        ];
    }
}
