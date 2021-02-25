<?php

namespace App\Models\System;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $primaryKey = 'banner_id';
    protected $is_delete  = 0;

    public function getBannerCoverAttribute($key)
    {
        if ( empty($key) ) return $key;
        return Storage::url($key);
    }

    public function setBannerCoverAttribute($key)
    {
        if ( !empty($key) ) {
            $this->attributes['banner_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }

    /**
     * 前端：获取Banner
     *
     * @param  bool  $force_update 是否强制更新缓存
     *
     * @return mixed
     */
    public static function getBannersByWeb(bool $force_update = false)
    {
        $cache_key = CacheKeys::CACHE_WEB_BANNERS;
        // 强制更新缓存
        if ( $force_update ) {
            // 删除缓存key
            Cache::forget($cache_key);
        }
        $banners = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::where('is_check', 1)->orderBy('banner_sort', 'ASC')->limit(20)->get();
        });
        return $banners;
    }
}
