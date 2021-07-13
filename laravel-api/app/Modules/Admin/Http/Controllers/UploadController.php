<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\UploadFile;
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
    public function file(Request $request, $file = 'file')
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

        return $this->successJson($path, '上传成功', ['file_url' => $uploadFile->file_url]);
    }

    /**
     * 批量上传图片
     *
     * @param Request $request
     * @param string $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function files(Request $request, $file = 'files')
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
            $uploadFile = UploadFile::addRecord($path, $request->file($file));
            $path[] = $uploadFile->file_url;
        }
        return $this->successJson($path, '上传成功！');
    }
}
