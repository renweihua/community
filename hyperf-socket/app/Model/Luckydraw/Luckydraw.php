<?php

namespace App\Model\Luckydraw;

use App\Model\Model;

/**
 * App\Model\Luckydraw\Luckydraw
 *
 * @property int $activity_id 活动Id
 * @property string $activity_name 活动名称
 * @property int $turntable_config 转盘配置对应的几个组【4、6、8、10】
 * @property int $is_open 是否开启该抽奖活动
 * @property int $is_delete 是否删除：0.否；1.是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereActivityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereTurntableConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Luckydraw whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Luckydraw extends Model
{
    protected $primaryKey = 'activity_id';
    protected $is_delete = 0;
}
