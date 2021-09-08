<?php

declare (strict_types = 1);

namespace App\Model;

/**
 */
class FileModel extends Model
{
    protected $primaryKey = 'file_id';
    // 追加字段：完整展示图片的路径
    protected $appends = ['file_path'];

    public function getFilePathAttribute($value)
    {
        if ($this->attributes['storage'] == 'local'){
            return env('APP_URL') . '/' . $this->attributes['file_name'];
        }else{
            return $this->attributes['host_url'] . '/'  . $this->attributes['file_name'];
        }
    }

    public static function addRecord($file_name, $file, $user_id = 0)
    {
        return self::query()->create(['storage' => 'local', 'host_url' => '', 'file_name' => $file_name, 'file_size' => $file->getSize(), 'file_type' => $file->getClientMediaType(), 'extension' => $file->getExtension(), 'user_id' => $user_id]);
    }

    public static function addRecordInfo($file_name, $file_size, $file_type, $extension = 'jpg', $user_id = 0)
    {
        $segments = explode('.', $file_type);
        return self::query()
                   ->create(['storage' => 'local', 'host_url' => '', 'file_name' => $file_name, 'file_size' => $file_size,
                       'file_type' => $file_type, 'extension' => $extension ?: end($segments), 'user_id' => $user_id]);
    }

    /**
     * base64图片录入
     *
     * @param       $file_name
     * @param  int  $file_size
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public static function addRecordBase64($file_name, $file_size = 0)
    {
        return self::query()
                   ->create(['storage' => 'local', 'host_url' => '', 'file_name' => $file_name, 'file_size' => $file_size, 'file_type' => 'image/png', 'extension' => 'png',]);
    }

    /**
     * 图片的访问路径
     *
     * @param  string  $name
     *
     * @return string
     */
    public static function getFilePath(string $name) : string
    {
        return '/uploads/' . date('Y-m-d') . '/' . $name;
    }

    /**
     * 图片在服务端的绝对路径
     *
     * @param  string  $name
     *
     * @return string
     */
    public static function getAbsolutePath(string $name = '') : string
    {
        $dir_path = BASE_PATH . '/public/uploads/' . date('Y-m-d');
        $dir_prefix = '';
        foreach (explode('/', $dir_path) as $dir) {
            $dir_prefix .= '/' . $dir;
            if ( !is_dir($dir_prefix) ) mkdir($dir_prefix, 0755);
        }
        return $dir_path . '/' . (empty($name) ? '' : $name);
    }

    /**
     * 生成唯一的名称
     *
     * @param  string  $extension
     * @param  string  $type
     * @param  int     $length
     *
     * @return string
     */
    public static function getUniqidName(string $extension = 'png', string $type = 'img', int $length = 20) : string
    {
        return rand_str(20) . '.' . $extension;
        return uniqid($type, true) . date('Ymdhis') . '.' . $extension;
    }

    /**
     * 按照Ids进行批量搜索
     *
     * @param $file_ids
     *
     * @return array
     */
    public static function getFilePathByIds($file_ids)
    {
        $list = self::getInstance()
                    ->cnpscyWhereIn('file_id', $file_ids)
                    ->select('file_id', 'file_name', 'storage', 'file_url')
                    ->get()
                    ->toArray();
        return array_column($list, 'file_path', 'file_id');
    }
}
