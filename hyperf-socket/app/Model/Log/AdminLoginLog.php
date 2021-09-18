<?php

namespace App\Model\Log;

use App\Model\MonthModel;
use App\Model\Rabc\Admin;

/**
 * App\Model\Log\AdminLoginLog
 *
 * @property int $log_id
 * @property int $admin_id 管理员Id
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property int $log_status 登录状态：1：成功；0：失败
 * @property string $description 描述
 * @property string $log_action 请求方法
 * @property string $log_method 请求类型/请求方式
 * @property string $request_data 请求参数
 * @property int $is_delete 是否删除：1：是；0：否
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string|null $log_duration
 * @property-read Admin $admin
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereLogAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereLogDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereLogMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereLogStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AdminLoginLog extends MonthModel
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

    public function add(int $admin_id = 0, int $log_status = 1, $description = '登录成功')
    {
        $ip_agent = get_client_info();
        return $this->setMonthTable()->create([
            'admin_id' => $admin_id,
            'created_ip'   => $ip_agent['ip'] ?? get_ip(),
            'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            'log_status' => $log_status,
            'description' => $description,
            'log_action'   => request()->route()->getActionName(),
            'log_method'   => request()->getMethod(),
            'log_duration' => microtime(true) - LARAVEL_START,
            'request_data' => json_encode(request()->all()),
        ]);
    }
}
