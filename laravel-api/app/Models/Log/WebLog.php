<?php

namespace App\Models\Log;

use App\Models\MonthModel;

/**
 * App\Models\Log\WebLog
 *
 * @property int $log_id 前台API请求日志记录表
 * @property int $user_id 会员主键
 * @property string $log_description 描述
 * @property string $this_url 当前URL
 * @property string $request_url 请求URL
 * @property string $log_action 请求方法
 * @property string $log_method 请求类型/请求方式
 * @property string $created_ip 创建时的IP
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string $browser_type 创建时浏览器类型
 * @property mixed|null $request_data 请求参数
 * @property string $log_duration 请求时长（s）
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereLogAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereLogDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereLogDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereLogMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereRequestUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereThisUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebLog whereUserId($value)
 * @mixin \Eloquent
 */
class WebLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function getLogDurationAttribute($key)
    {
        return floatval($key);
    }
}
