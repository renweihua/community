<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Models\UploadFile;
use App\Modules\Bbs\Http\Controllers\BbsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends BbsController
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

        $path = $request->file($file)->storePublicly(
            date('Ym'),
            config('filesystems')
        );

        // 添加文件库记录
        $uploadFile = UploadFile::addRecord($path, $request->file($file));

        return $this->successJson($path, '上传成功', ['path_url' => $uploadFile->file_url]);
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

        foreach ($request->file($file) as $file){
            $path = $file->storePublicly(
                date('Ym'),
                config('filesystems')
            );

            // 添加文件库记录
            $uploadFile = UploadFile::addRecord($path, $file);
            $paths[] = $uploadFile->file_url;
        }
        return $this->successJson($paths, '上传成功！');
    }
}
