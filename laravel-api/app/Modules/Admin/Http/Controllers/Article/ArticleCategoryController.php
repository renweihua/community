<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleCategoryRequest;
use App\Modules\Admin\Services\ArticleCategoryService;
use Illuminate\Http\JsonResponse;

class ArticleCategoryController extends BaseController
{
    public function __construct(ArticleCategoryService $articleCategoryService)
    {
        $this->service = $articleCategoryService;
    }

    public function create(ArticleCategoryRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(ArticleCategoryRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
