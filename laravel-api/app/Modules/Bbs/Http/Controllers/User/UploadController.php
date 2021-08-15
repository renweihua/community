<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Models\UploadFile;
use App\Modules\Bbs\Http\Controllers\BbsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Overtrue\CosClient\ObjectClient;
use Ramsey\Uuid\Uuid;

class UploadController extends BbsController
{
    protected $config;

    public function __construct()
    {
        $cos_config = config('filesystems.disks')['cosv5'];

        $this->config = [
            // 必填，app_id、secret_id、secret_key
            // 可在个人秘钥管理页查看：https://console.cloud.tencent.com/capi
            'app_id' => $cos_config['credentials']['appId'],
            'secret_id' => $cos_config['credentials']['secretId'],
            'secret_key' => $cos_config['credentials']['secretKey'],

            // 可选，地域列表请查看 https://cloud.tencent.com/document/product/436/6224
            'region' => $cos_config['region'],

            // 可选，仅在调用不同的接口时按场景必填
            'bucket' => $cos_config['bucket'], // 使用 Bucket 接口时必填

            // 可选，签名有效期，默认 60 分钟
            'signature_expires' => '+60 minutes',

            // 访问域名
            'cdn' => $cos_config['cdn'],
        ];
    }

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

        $object = new ObjectClient($this->config);
        $key = date('Ym') . '/' . $file->getClientOriginalName();
        $object->putObject($key, file_get_contents($file->getRealPath()));
        $file_url = $object->getObjectUrl($key);

//        $path = $request->file($file)->storePublicly(
//            date('Ym'),
//            config('filesystems')
//        );

        // 添加文件库记录
        $uploadFile = UploadFile::addRecord($file_url, $file, 'cos', $this->config['cdn']);

        return $this->successJson($file_url, '上传成功', ['path_url' => $uploadFile->file_url]);
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

        $object = new ObjectClient($this->config);

        foreach ($request->file($file) as $file){
//            $path = $file->storePublicly(
//                date('Ym'),
//                config('filesystems')
//            );

            $key = date('Ym') . '/' . $file->getClientOriginalName();
            $object->putObject($key, file_get_contents($file->getRealPath()));
            $file_url = $object->getObjectUrl($key);

            // 添加文件库记录
            $uploadFile = UploadFile::addRecord($file_url, $file, 'cos', $this->config['cdn']);
            $paths[] = $uploadFile->file_url;
        }
        return $this->successJson($paths, '上传成功！');
    }
}
