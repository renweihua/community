<?php

namespace App\Models\Rabc;

use App\Models\Model;

/**
 * App\Models\Rabc\AdminRole
 *
 * @property int $role_id 角色表
 * @property string $role_name 角色名称
 * @property string $role_remarks 备注
 * @property int $is_check 是否可用：1：可用；0：禁用
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rabc\AdminMenu[] $menus
 * @property-read int|null $menus_count
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereRoleRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    protected $primaryKey = 'role_id';
    protected $is_delete = 0;

    public function menus()
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_role_with_menus', 'role_id', 'menu_id')
            ->where(['is_delete' => 0, 'is_check' => 1])
            ->orderBy('menu_sort', 'ASC');
    }

    /**
     * @Function         grantMenus
     *
     * @param $menus
     *
     * @return bool
     * @author           : cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:给角色赋予权限
     * @englishAnnotation:
     */
    public function grantMenus($menus)
    {
        return AdminRoleWithMenu::create($menus);
    }

    /**
     * @Function         deleteMenus
     *
     * @param $menus
     *
     * @return mixed
     * @author           : cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:取消角色赋予的权限
     * @englishAnnotation:
     */
    public function deleteMenus($menus)
    {
        return AdminRoleWithMenu::where($menus)->delete();
    }
}
