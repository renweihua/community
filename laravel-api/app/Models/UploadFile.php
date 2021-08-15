<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

/**
 * App\Models\UploadFile
 *
 * @property int $file_id
 * @property int $user_id 会员Id
 * @property string $storage 存储方式
 * @property string $host_url 存储域名
 * @property string $file_name 文件路径
 * @property int $file_size 文件大小(字节)
 * @property string $file_type 文件类型
 * @property string $extension 文件扩展名
 * @property int $is_delete 软删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property-read mixed $file_url
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereHostUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereUserId($value)
 * @mixin \Eloquent
 */
class UploadFile extends Model
{
    protected $primaryKey = 'file_id';
    protected $is_delete = 0;

    protected $appends = ['file_url'];

    public function getFileUrlAttribute($key)
    {
        if ($this->attributes['storage'] == 'local'){
            return Storage::url($this->attributes['file_name']);
        }
        // 非本地文件，获取文件时自动追加域名
        return $this->attributes['host_url'] . '/' . trim($this->attributes['file_name'],'/');
    }

    public function setFileNameAttribute($key): void
    {
        // 非本地文件上传，存储文件时自动移除域名
        if ($this->attributes['storage'] != 'local'){
            $key = str_replace($this->attributes['host_url'], '', $key);
        }
        $this->attributes['file_name'] = $key;
    }

    /**
     * 添加文件库上传记录
     *
     * @param  string  $file_name
     * @param          $file
     * @param  string  $storage  存储引擎
     * @param  string  $host_url 存储域名
     *
     * @return UploadFile
     */
    public static function addRecord(string $file_name, $file, $storage = 'local', $host_url = '')
    {
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
