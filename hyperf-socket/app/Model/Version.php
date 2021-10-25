<?php

namespace App\Models;

/**
 * App\Model\Version
 *
 * @property int $version_id 版本表
 * @property string $version_name 名称
 * @property string $version_number 版本号
 * @property string|null $version_content 内容
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除
 * @property int|null $version_sort 排序
 * @property string|null $publish_date 版本的发布时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Version newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Version newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Version query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Version wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereVersionContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereVersionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereVersionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Version whereVersionSort($value)
 * @mixin \Eloquent
 */
class Version extends Model
{
    protected $primaryKey = 'banner_id';
    protected $is_delete  = 0;
}
