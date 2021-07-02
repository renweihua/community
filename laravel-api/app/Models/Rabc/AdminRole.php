<?php

namespace App\Models\Rabc;

use App\Models\Model;

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
