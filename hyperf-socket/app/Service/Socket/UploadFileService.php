<?php

namespace App\Service\Socket;

use App\Model\Model;
use App\Model\Upload\UploadFile;
use App\Service\Service;

/**
 * 文件上传服务层
 *
 * Class UploadFileService
 *
 * @package App\Service\Socket
 */
class UploadFileService extends Service
{
    /**
     * base64文件上传
     *
     * @param  string  $base64img
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public static function base64(string $base64img) : Model
    {
        if ( strstr($base64img, ",") ) {
            $base64img = explode(',', $base64img);
            $base64img = $base64img[1];
        }
        $uploadFile = UploadFile::getInstance();
        // 生成文件名称
        $name = $uploadFile->getUniqidName();

        /**
         * 获取base64数据大小
         */
        $base64 = str_replace('data:image/jpeg;base64,', '', $base64img);
        $base64 = str_replace('=', '', $base64);
        $img_len = strlen($base64);
        $file_size = $img_len - ($img_len / 8) * 2;
        $file_size = number_format(($file_size / 1024), 2) . 'kb';

        // getAbsolutePath：文件在服务器的绝对路径，并写入文件
        file_put_contents($uploadFile->getAbsolutePath($name), base64_decode($base64img));

        // 文件访问路径
        return $uploadFile->addRecordBase64($uploadFile->getFilePath($name), $file_size);
    }
}