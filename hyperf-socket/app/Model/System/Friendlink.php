<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Constants\CacheKeys;
use App\Model\Model;
use App\Model\Upload\UploadFile;
use App\Utils\Cache;

/**
 */
class Friendlink extends Model
{
    protected $primaryKey = 'link_id';
    public $is_delete = 0;

    public function cover()
    {
        return $this->hasOne(UploadFile::class, 'file_id', 'link_img');
    }

    public function getLinkCoverAttribute($key)
    {
        if (empty($key)) return $key;
        return get_file_url($key);
    }

    public function setLinkCoverAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['link_cover'] = set_file_url($key);
        }
    }

    /**
     * 前端：获取友情链接
     *
     * @param  bool  $force_update 是否强制更新缓存
     *
     * @return mixed
     */
    public static function getFriendlinksByWeb(bool $force_update = false)
    {
        $cache_key = CacheKeys::CACHE_WEB_FRIENDLINKS;
        // 强制更新缓存
        if ( $force_update ) {
            // 删除缓存key
            Cache::delete($cache_key);
        }
        $lists = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::where('is_check', 1)->orderBy('link_sort', 'ASC')->get();
        });
        return $lists;
    }
}