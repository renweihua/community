<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\Dynamic\Dynamic;
use App\Modules\Bbs\Http\Requests\DynamicIdRequest;
use App\Modules\Bbs\Services\DynamicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DynamicController extends BbsController
{
    public function __construct(DynamicService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function detail(DynamicIdRequest $request)
    {
        $data = $request->validated();
        if ($detail = $this->service->detail((int)$data['dynamic_id'])) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
