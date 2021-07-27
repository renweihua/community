<?php

namespace App\Models\Log;

use App\Models\MonthModel;
use App\Models\Rabc\Admin;

/**
 * App\Models\Log\AdminLog
 *
 * @property int $log_id
 * @property int $admin_id 管理员Id
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property int $log_status 状态：1：成功；0：失败
 * @property string $description 描述
 * @property string $log_action 请求方法
 * @property string $log_method 请求类型/请求方式
 * @property string $request_data 请求参数
 * @property int $is_delete 是否删除：1：是；0：否
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string $log_duration
 * @property string $request_url
 * @property-read Admin $admin
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereLogAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereLogDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereLogMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereLogStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereRequestUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLog whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AdminLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function getLogDurationAttribute($key)
    {
        return floatval($key);
    }
}
