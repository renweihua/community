<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleLabelRequest;
use App\Modules\Admin\Services\ArticleLabelService;

class ArticleLabelController extends BaseController
{
    public function __construct(ArticleLabelService $articleLabelService)
    {
        $this->service = $articleLabelService;
    }

    public function create(ArticleLabelRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ArticleLabelRequest $request)
    {
        return $this->updateService($request);
    }
}
