<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\FriendlinkRequest;
use App\Modules\Admin\Services\FriendlinkService;
use Illuminate\Http\JsonResponse;

class FriendlinkController extends BaseController
{
    public function __construct(FriendlinkService $friendlinkService)
    {
        $this->service = $friendlinkService;
    }

    public function create(FriendlinkRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(FriendlinkRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
