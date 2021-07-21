<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class UploadFile extends Model
{
    protected $primaryKey = 'file_id';
    protected $is_delete = 0;

    protected $appends = ['file_url'];

    public function getFileUrlAttribute($key)
    {
        if ($this->attributes['storage'] == 'local'){
            return Storage::url($this->attributes['file_name']);
            // return env('APP_URL') . '/' . trim($this->attributes['file_name'],'/');
        }
        return $this->attributes['host_url'] . '/' . trim($this->attributes['file_name'],'/');
    }

    /**
     * 添加文件库上传记录
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return UploadFile
     */
    public static function addRecord($file_name, $file)
    {
        // 存储引擎
        $storage = 'local';
        // 存储域名
        $host_url = '';
        // 添加文件库记录
        return UploadFile::create([
            'storage' => $storage,
            'host_url' => $host_url,
            'file_name' => $file_name,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }
}
