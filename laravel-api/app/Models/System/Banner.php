<?php

namespace App\Models\System;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\System\Banner
 *
 * @property int $banner_id
 * @property string $banner_title 标题
 * @property int $banner_type Banner类型：common_banner_type_list
 * @property string $banner_cover 封面
 * @property string|null $banner_link 外链
 * @property string $banner_words 文字描述
 * @property int $banner_sort 排序[从小到大]
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property int $is_check 是否可用：1：可用；0：禁用
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBannerWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereUpdatedTime($value)
 * @mixin \Eloquent
 */
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
