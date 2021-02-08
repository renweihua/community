<?php

namespace App\Modules\Admin\Http\Requests\Article;

use App\Models\Article\ArticleLabel;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ArticleLabelRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = ArticleLabel::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',label_name' . $this->validate_id
            ],
        ];
    }

    public function messages()
    {
        return [
            'label_name.required' => '请输入标签名称！',
            'label_name.max'      => '标签名称字数不可超过 256！',
            'label_name.unique'   => '标签名称已存在，请更换！',
        ];
    }
}
