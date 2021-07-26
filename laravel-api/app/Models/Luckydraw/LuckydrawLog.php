<?php

namespace App\Models\Luckydraw;

use App\Models\Model;

/**
 * App\Models\Luckydraw\LuckydrawLog
 *
 * @property int $log_id 活动抽奖记录Id
 * @property int $user_id 会员Id
 * @property int $activity_id 活动Id
 * @property int $detail_id 活动配置Id
 * @property int $reward_type 奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等
 * @property string $reward_quota 奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】
 * @property int $product_id 产品Id
 * @property string $created_ip 创建时的IP
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_receive 是否已领取：0.否；1.是
 * @property int $is_delete 是否删除：0.否；1.是
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereIsReceive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereRewardQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereRewardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawLog whereUserId($value)
 * @mixin \Eloquent
 */
class LuckydrawLog extends Model
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;
}
