<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleCategoryRequest;
use App\Modules\Admin\Services\ArticleCategoryService;

class ArticleCategoryController extends BaseController
{
    public function __construct(ArticleCategoryService $articleCategoryService)
    {
        $this->service = $articleCategoryService;
    }

    public function create(ArticleCategoryRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ArticleCategoryRequest $request)
    {
        return $this->updateService($request);
    }
}
