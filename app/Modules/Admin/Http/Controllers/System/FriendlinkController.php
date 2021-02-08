<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\FriendlinkRequest;
use App\Modules\Admin\Services\FriendlinkService;

class FriendlinkController extends BaseController
{
    public function __construct(FriendlinkService $friendlinkService)
    {
        $this->service = $friendlinkService;
    }

    public function create(FriendlinkRequest $request)
    {
        return $this->createService($request);
    }

    public function update(FriendlinkRequest $request)
    {
        return $this->updateService($request);
    }
}
