<?php

namespace App\Modules\Bbs\Http\Requests\User;

use App\Modules\Bbs\Http\Requests\BaseRequest;

class BackgroundCoverRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'background_cover' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'background_cover.required' => '请上传背景封面图！',
        ];
    }
}
