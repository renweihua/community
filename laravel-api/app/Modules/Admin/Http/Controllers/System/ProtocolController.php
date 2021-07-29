<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\ProtocolRequest;
use App\Modules\Admin\Services\ProtocolService;
use Illuminate\Http\JsonResponse;

class ProtocolController extends BaseController
{
    public function __construct(ProtocolService $protocolService)
    {
        $this->service = $protocolService;
    }

    public function create(ProtocolRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(ProtocolRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
