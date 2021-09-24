<?php

namespace App\Model\Luckydraw;

use App\Model\Model;

/**
 * App\Model\Luckydraw\LuckydrawConfig
 *
 * @property int $detail_id 活动详情Id
 * @property int $activity_id 活动Id
 * @property string $reward_name 几等奖
 * @property int $reward_type 奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等
 * @property string $reward_quota 奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】
 * @property int $product_id 产品Id
 * @property int $awards_num 该奖励的发奖次数
 * @property string $probability_of_winning 获奖的概率
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除：0.否；1.是
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereAwardsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereProbabilityOfWinning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereRewardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereRewardQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereRewardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawConfig whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class LuckydrawConfig extends Model
{
    protected $primaryKey = 'detail_id';
    protected $is_delete = 0;
}
