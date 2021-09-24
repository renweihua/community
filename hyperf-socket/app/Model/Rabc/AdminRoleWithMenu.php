<?php

namespace App\Model\Rabc;

use App\Model\Model;

/**
 * App\Model\Rabc\AdminRoleWithMenu
 *
 * @property int $with_id
 * @property int $role_id 角色Id
 * @property int $menu_id 菜单Id
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRoleWithMenu whereWithId($value)
 * @mixin \Eloquent
 */
class AdminRoleWithMenu extends Model
{
    protected $primaryKey = 'with_id';
    public $timestamps = false;

    public function getMenuIdsByRoles(array $role_ids)
    {
        return array_unique(array_column($this->whereIn('role_id', $role_ids)->select('menu_id')->get()->toArray(), 'menu_id'));
    }
}
