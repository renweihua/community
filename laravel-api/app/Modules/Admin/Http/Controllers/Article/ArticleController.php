<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleRequest;
use App\Modules\Admin\Services\ArticleService;

class ArticleController extends BaseController
{
    public function __construct(ArticleService $articleService)
    {
        $this->service = $articleService;
    }

    public function create(ArticleRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ArticleRequest $request)
    {
        return $this->updateService($request);
    }
}
