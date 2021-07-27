<?php

namespace App\Models\Log;

use App\Models\MonthModel;

/**
 * App\Models\Log\UserLog
 *
 * @property int $log_id 会员登录日志记录表
 * @property int $user_id 用户的id
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property int $log_status 状态：1.成功；0.失败
 * @property string $description 描述
 * @property string $log_duration 请求时长
 * @property string $log_action 请求方法
 * @property string $log_method 请求类型/请求方式
 * @property mixed|null $request_data 请求参数
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除：1：是；0：否
 * @property int $is_public 是否展示：1.展示；0.会员删除；2.管理员删除
 * @property int $log_type 日志类型【0.登陆；1.退出；2.签到；……】
 * @property int $login_type 登录类型【0.普通登录】
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLoginType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUserId($value)
 * @mixin \Eloquent
 */
class UserLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;
}
