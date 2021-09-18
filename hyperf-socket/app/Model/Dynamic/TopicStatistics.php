<?php

namespace App\Model\Dynamic;

use App\Model\Model;

/**
 * App\Model\Dynamic\TopicStatistics
 *
 * @property int $with_id 话题 统计表
 * @property int $topic_id 话题Id
 * @property int $follow_num 关注数量
 * @property int $dynamic_num 动态数量【包含文章、视频、动态、提问】
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereDynamicNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereFollowNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicStatistics whereWithId($value)
 * @mixin \Eloquent
 */
class TopicStatistics extends Model
{
    protected $primaryKey = 'relation_id';
}
