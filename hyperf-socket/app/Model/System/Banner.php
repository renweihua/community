<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Constants\CacheKeys;
use App\Model\Model;
use App\Utils\Cache;

/**
 */
class Banner extends Model
{
    protected $primaryKey = 'banner_id';
    protected $is_delete  = 0;

    public function getBannerCoverAttribute($key)
    {
        if ( empty($key) ) return $key;
        return get_file_url($key);
    }

    public function setBannerCoverAttribute($key)
    {
        if ( !empty($key) ) {
            $this->attributes['banner_cover'] = set_file_url($key);
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
            Cache::delete($cache_key);
        }
        $lists = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::where('is_check', 1)->orderBy('banner_sort', 'ASC')->limit(20)->get();
        });
        return $lists;
    }
}