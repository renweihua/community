<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleLabelRequest;
use App\Modules\Admin\Services\ArticleLabelService;
use Illuminate\Http\JsonResponse;

class ArticleLabelController extends BaseController
{
    public function __construct(ArticleLabelService $articleLabelService)
    {
        $this->service = $articleLabelService;
    }

    public function create(ArticleLabelRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(ArticleLabelRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
