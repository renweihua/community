<?php

namespace App\Modules\Admin\Http\Controllers;

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

        return $this->successJson($path, '上传成功', ['path_url' => Storage::url($path)]);
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
            $path[] = Storage::url($file->storePublicly(
                date('Ym'),
                config('filesystems')
            ));
        }
        return $this->successJson($path, '上传成功！');
    }
}
