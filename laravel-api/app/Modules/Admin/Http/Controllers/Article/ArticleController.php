<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleRequest;
use App\Modules\Admin\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends BaseController
{
    public function __construct(ArticleService $articleService)
    {
        $this->service = $articleService;
    }

    public function create(ArticleRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(ArticleRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
