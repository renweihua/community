<?php

namespace App\Models\System;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\System\Friendlink
 *
 * @property int $link_id
 * @property string $link_name 名称
 * @property string $link_url 链接URL
 * @property string $link_cover 链接图标
 * @property int $link_sort 排序[从小到大]
 * @property int $is_check 是否可用：1：可用；0：禁用
 * @property int $open_window 是否打开新窗口：1：是；0：否
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereLinkCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereLinkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereLinkSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereOpenWindow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friendlink whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Friendlink extends Model
{
    protected $primaryKey = 'link_id';
    protected $is_delete = 0;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::updated(function(){
            self::getFriendlinksByWeb(true);
        });

        self::saved(function(){
            self::getFriendlinksByWeb(true);
        });

        self::deleted(function(){
            self::getFriendlinksByWeb(true);
        });
    }

    public function getLinkCoverAttribute($key)
    {
        if (empty($key)) return $key;
        return Storage::url($key);
    }

    public function setLinkCoverAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['link_cover'] = str_replace(Storage::url('/'), '', $key);
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
            Cache::forget($cache_key);
        }
        $banners = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::where('is_check', 1)->orderBy('link_sort', 'ASC')->get();
        });
        return $banners;
    }
}
