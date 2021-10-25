<?php

namespace App\Model\Luckydraw;

use App\Model\Model;

/**
 * App\Model\Luckydraw\LuckydrawReward
 *
 * @property int $reward_id 领奖记录Id
 * @property int $user_id 会员Id
 * @property int $log_id 活动抽奖记录Id
 * @property int $product_id 产品Id
 * @property mixed $receive_info 收货信息【JSON】
 * @property mixed $express_info 快递信息【JSON】
 * @property int $reward_quota 获取商品的数量
 * @property string $user_remarks 会员备注
 * @property string $created_ip 创建时的IP
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $delivery_time 发货时间
 * @property int $collect_time 收货时间
 * @property int $reward_status 订单状态：0.待确认；1.已确认/待支付；2.已支付/待发货；3.已发货/待收货；4.已完成；5.已取消；6.已关闭
 * @property int $is_delete 是否删除：0.否；1.是
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereCollectTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereExpressInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereReceiveInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereRewardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereRewardQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereRewardStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawReward whereUserRemarks($value)
 * @mixin \Eloquent
 */
class LuckydrawReward extends Model
{
    protected $primaryKey = 'reward_id';
    protected $is_delete = 0;
}
