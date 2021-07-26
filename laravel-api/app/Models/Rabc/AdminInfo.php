<?php

namespace App\Models\Rabc;

use App\Models\Model;

/**
 * App\Models\Rabc\AdminInfo
 *
 * @property int $admin_id 管理员Id
 * @property int $login_num 登录次数
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereLoginNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminInfo whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AdminInfo extends Model
{
    protected $primaryKey = 'admin_id';
}
