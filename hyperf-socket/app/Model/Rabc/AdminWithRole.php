<?php

namespace App\Model\Rabc;

use App\Model\Model;

/**
 * App\Model\Rabc\AdminWithRole
 *
 * @property int $with_id
 * @property int $role_id 角色Id
 * @property int $admin_id 管理员Id
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWithRole whereWithId($value)
 * @mixin \Eloquent
 */
class AdminWithRole extends Model
{
    protected $primaryKey = 'with_id';
}
