<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Services\FileService;
use Illuminate\Http\Request;

class FileController extends BaseController
{
    public function __construct(FileService $fileService)
    {
        $this->service = $fileService;
    }

    /**
     * 移动文件至指定分组
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFileGroup(Request $request)
    {
        if ($this->service->removeFileGroup($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }
}
