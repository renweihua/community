<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Database\factories\DynamicFactory;
use Illuminate\Support\Facades\Storage;

class Dynamic extends Model
{
    protected $primaryKey = 'dynamic_id';
    protected $is_delete  = 0;

    /**
     * 只查询 启用 的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheck($query)
    {
        return $query->where('is_check', 1);
    }

    protected static function newFactory()
    {
        return DynamicFactory::new();
    }

    /**
     * 获取多图，自动转成数组
     *
     * @param $key
     * @return false|string[]
     */
    public function getDynamicImagesAttribute($key)
    {
        if (empty($key)) return [];
        $imgs = explode(',', $key);
        foreach ($imgs as &$img) {
            $img = Storage::url($img);
        }
        return $imgs;
    }

    /**
     * 设置动态的图片
     *
     * @param $key
     */
    public function setDynamicImagesAttribute($key)
    {
        if ( !empty($key)) {
            $key = explode(',', $key);
            foreach ($key as &$value) {
                $value = str_replace(Storage::url('/'), '', $value);
            }
            $this->attributes['dynamic_images'] = implode(',', $key);
        }
    }

    /**
     * 获取视频地址
     *
     * @param $value
     *
     * @return string
     */
    public function getVideoPathAttribute($value)
    {
        if (empty($value)) return '';
        return Storage::url($value);
    }

    /**
     * 设置动态的视频地址
     *
     * @param $value
     */
    public function setVideoPathAttribute($value)
    {
        if ( !empty($value)) {
            $this->attributes['video_path'] = str_replace(Storage::url('/'), '', $value);
        }
    }

    /**
     * 获取视频信息
     * 
     * @param $value
     *
     * @return mixed|object
     */
    public function getVideoInfoAttribute($value)
    {
        if (empty($value)) return (object)[];
        return my_json_decode($value);
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }


    // 是否收藏
    public function isCollection()
    {
        return $this->hasOne(DynamicCollection::class, $this->primaryKey, $this->primaryKey);
    }

    // 收藏
    public function collection()
    {
        return $this->hasMany(DynamicCollection::class, $this->primaryKey, $this->primaryKey);
    }

    // 是否点赞
    public function isPraise()
    {
        return $this->hasOne(DynamicPraise::class, $this->primaryKey, $this->primaryKey);
    }

    // 点赞
    public function praises()
    {
        return $this->hasMany(DynamicPraise::class, $this->primaryKey, $this->primaryKey);
    }
}
