<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\UploadFile;
use App\Services\CosService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends BaseController
{
    /**
     * 文件上传
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function file(Request $request, $file = 'file'): JsonResponse
    {
        if (empty($request->file($file))){
            return $this->errorJson('请上传文件！');
        }

        $file = $request->file($file);

        $file_url = CosService::getInstance()->put($file);

        return $this->successJson($file_url, '上传成功', ['path_url' => $file_url]);
    }

    /**
     * 批量上传图片
     *
     * @param Request $request
     * @param string $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function files(Request $request, $file = 'files'): JsonResponse
    {
        if (empty($request->file($file))){
            return $this->errorJson('请上传文件！');
        }

        $object = CosService::getInstance();

        foreach ($request->file($file) as $file){
            $file_url = $object->put($file);

            $paths[] = $file_url;
        }
        return $this->successJson($paths, '上传成功！');
    }
}
