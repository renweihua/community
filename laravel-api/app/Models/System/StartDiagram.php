<?php

namespace App\Models\System;

use App\Models\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * App\Models\System\StartDiagram
 *
 * @property int $id 启动图配置表
 * @property string $diagram_name 名称
 * @property false|string[] $diagram_cover 封面图
 * @property int $diagram_sort 排序
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereDiagramCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereDiagramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereDiagramSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|StartDiagram whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class StartDiagram extends Model
{
    protected $is_delete = 0;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::updated(function(){
            self::getStartDiagrams(true);
        });

        self::saved(function(){
            self::getStartDiagrams(true);
        });

        self::deleted(function(){
            self::getStartDiagrams(true);
        });
    }

    /**
     * 获取启动图
     *
     * @param $key
     *
     * @return false|string[]
     */
    public function getDiagramCoverAttribute($key)
    {
        if (empty($key)) return '';
        return Storage::url($key);
    }

    /**
     * 设置启动图
     *
     * @param $key
     */
    public function setDiagramCoverAttribute($key)
    {
        if ( !empty($key)) {
            $this->attributes['diagram_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }

    public static function getStartDiagrams(bool $force_update = false)
    {
        $cache_key = 'start_diagrams';
        // 强制更新缓存
        if ( $force_update ) {
            // 删除缓存key
            Cache::forget($cache_key);
        }
        return Cache::remember($cache_key, Carbon::now()->addDays(10 + rand(0, 7)), function() {
            return self::select(['diagram_name', 'diagram_cover'])->orderBy('diagram_sort')->get();
        });
    }
}
