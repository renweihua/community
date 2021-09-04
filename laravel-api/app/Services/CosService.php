<?php

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Models\UploadFile;
use Illuminate\Http\JsonResponse;
use Overtrue\CosClient\ObjectClient;

class CosService extends Service
{
    protected $config;

    public function __construct()
    {
        $cos_config = config('filesystems.disks')['cosv5'];

        $this->config = [
            // 必填，app_id、secret_id、secret_key
            // 可在个人秘钥管理页查看：https://console.cloud.tencent.com/capi
            'app_id'            => $cos_config['credentials']['appId'],
            'secret_id'         => $cos_config['credentials']['secretId'],
            'secret_key'        => $cos_config['credentials']['secretKey'],

            // 可选，地域列表请查看 https://cloud.tencent.com/document/product/436/6224
            'region'            => $cos_config['region'],

            // 可选，仅在调用不同的接口时按场景必填
            'bucket'            => $cos_config['bucket'],
            // 使用 Bucket 接口时必填

            // 可选，签名有效期，默认 60 分钟
            'signature_expires' => '+60 minutes',

            // 访问域名
            'cdn'               => $cos_config['cdn'],
        ];
    }

    /**
     * 文件上传
     *
     * @param          $file
     * @param  string  $file_name
     * @param  string  $folder
     *
     * @return string
     * @throws \App\Exceptions\InvalidRequestException
     * @throws \Overtrue\CosClient\Exceptions\InvalidConfigException
     */
    public function put($file, $file_name = '', $folder = '', int $file_size = 0, $file_type = 'image/jepg', $extension = 'jpg') : string
    {
        if (empty($file)){
            throw new InvalidRequestException('请上传文件！');
        }
        $object = new ObjectClient($this->config);
        if ( is_string($file) ) {
            $key = (empty($folder) ? date('Ym') : ($folder)) . '/' . $file_name;
            $content = $file;
        } else {
            $key = (empty($folder) ? '' : ($folder . '/')) . date('Ym') . '/' . $file->getClientOriginalName();
            $content = file_get_contents($file->getRealPath());
        }
        $object->putObject($key, $content);
        $file_url = $object->getObjectUrl($key);

        // 添加文件库记录
        $uploadFile = UploadFile::addRecord(
            $file_url,
            $file,
            'cos',
            trim($object->baseUri, '/'),
            $file_size,
            $file_type,
            $extension
        );

        return $uploadFile->file_url;
    }
}
