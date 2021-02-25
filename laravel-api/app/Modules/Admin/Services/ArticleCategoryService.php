<?php

namespace App\Modules\Admin\Services;

use App\Models\Article\ArticleCategory;

class ArticleCategoryService extends BaseService
{
    public function __construct(ArticleCategory $article)
    {
        $this->model = $article;
    }

    public function lists(array $params) : array
    {
        return $this->model->getSelectLists();
    }

    public function getSelectLists($request)
    {
        return $this->model->getSelectLists();
    }

    public function create(array $params)
    {
        $result = parent::create($params); // TODO: Change the autogenerated stub;

        // 清除数据缓存
        $this->model->delCache();

        return $result;
    }

    public function update(array $params)
    {
        $result = parent::update($params); // TODO: Change the autogenerated stub;

        // 清除数据缓存
        $this->model->delCache();

        return $result;
    }

    public function delete(array $params)
    {
        parent::delete($params); // TODO: Change the autogenerated stub;

        // 清除数据缓存
        $this->model->delCache();

        return true;
    }

    public function changeFiledStatus(array $params)
    {
        $result = parent::changeFiledStatus($params); // TODO: Change the autogenerated stub;

        // 清除数据缓存
        $this->model->delCache();

        return $result;
    }
}