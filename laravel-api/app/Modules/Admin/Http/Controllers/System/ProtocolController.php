<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\ProtocolRequest;
use App\Modules\Admin\Services\ProtocolService;

class ProtocolController extends BaseController
{
    public function __construct(ProtocolService $protocolService)
    {
        $this->service = $protocolService;
    }

    public function create(ProtocolRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ProtocolRequest $request)
    {
        return $this->updateService($request);
    }
}
