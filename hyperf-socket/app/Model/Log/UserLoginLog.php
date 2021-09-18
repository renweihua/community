<?php

namespace App\Model\Log;

use App\Model\MonthModel;

/**
 * App\Model\Log\UserLoginLog
 *
 * @property int $log_id 会员登录日志记录表
 * @property int $user_id 用户的id
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property string $description 描述
 * @property string $log_duration 请求时长
 * @property int $is_public 是否展示：1.展示；0.会员删除；2.管理员删除
 * @property int $log_status 状态：1.成功；0.失败
 * @property int $is_delete 是否删除：1：是；0：否
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property mixed|null $request_data 请求参数
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereLogDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereLogStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginLog whereUserId($value)
 * @mixin \Eloquent
 */
class UserLoginLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function add(int $user_id = 0, int $log_status = 1, $description = '登录成功')
    {
        $ip_agent = get_client_info();
        return $this->setMonthTable()->create([
            'user_id' => $user_id,
            'created_ip'   => $ip_agent['ip'] ?? get_ip(),
            'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            'log_status' => $log_status,
            'description' => $description,
            'log_duration' => microtime(true) - LARAVEL_START,
            'request_data' => json_encode(request()->all()),
        ]);
    }
}
