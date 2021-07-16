<?php

namespace App\Models\Luckydraw;

use App\Models\Model;

/**
 * App\Models\Luckydraw\LuckydrawQualificationSource
 *
 * @property int $id
 * @property int $user_id 会员Id
 * @property int $source_type 抽奖机会的来源：1.签到；
 * @property string $created_ip 创建时的IP
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $change_type 变更类型：0.减少；1.增加
 * @property int $luckydraw_times 抽奖的次数【获取的次数】
 * @property mixed $express_info 扩展信息【JSON】
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereChangeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereExpressInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereLuckydrawTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawQualificationSource whereUserId($value)
 * @mixin \Eloquent
 */
class LuckydrawQualificationSource extends Model
{

}
