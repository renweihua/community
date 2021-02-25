<?php

namespace App\Modules\Admin\Services;

use App\Models\Article\ArticleLabel;

class ArticleLabelService extends BaseService
{
    public function __construct(ArticleLabel $article)
    {
        $this->model = $article;
    }

    public function lists(array $params): array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $query->where('label_name', 'LIKE', '%' . trim($search) . '%');
            }
        };
        return parent::lists($params); // TODO: Change the autogenerated stub
    }

    public function getSelectLists($request)
    {
        return $this->model->where(function($query) use ($request){
            $search = $request->input('search', '');
            // 可以按照名称进行搜索
            if (!empty($search)){
                $query->where('label_name', 'LIKE', '%' . trim($search) . '%');
            }
        })->orderBy($this->model->getKeyName(), 'ASC')->limit(100)->get();
    }
}