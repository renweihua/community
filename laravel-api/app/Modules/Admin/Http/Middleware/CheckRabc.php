<?php

namespace App\Modules\Admin\Http\Middleware;

use App\Models\Rabc\Admin;
use App\Models\Rabc\AdminMenu;
use App\Models\Rabc\AdminRoleWithMenu;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;

class CheckRabc
{
    use Json;

    protected $guard;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 权限认证
        $this->guard = 'admin';
        // 开始验证路由权限
        if (!$this->checkRabc($request, Auth()->guard($this->guard)->user()->getAuthIdentifier(), $load_error)){
            return $this->errorJson('无权限' . (empty($load_error) ? '！' : '，' . $load_error), -2);
        }

        return $next($request);
    }

    private function checkRabc($request, int $admin_id, &$load_error = ''):bool
    {
        // 超级管理员账户无需验证
        if ($admin_id == 1) return true;

        $roles = Admin::getInstance()->detail($admin_id)->roles->toArray();
        if (empty($roles)) return false;
        $role_ids = array_column($roles, 'role_id');
        if (empty($role_ids)) return false;
        $menu_ids = AdminRoleWithMenu::getInstance()->getMenuIdsByRoles($role_ids);
        if (empty($menu_ids)) return false;
        $menus = AdminMenu::getInstance()->getMenusByIdsForRabc($menu_ids)->toArray();
        if (empty($menus)) return false;

        // 获取当前路由
        $route_path = $request->route()->uri();
        // 检测是否存在当前API
        if (isset($menus[$route_path])){
            // 验证请求方式
            if ($menus[$route_path] == $request->getMethod()){
                return true;
            }
            $load_error = '请求方式有误！';
        }
        return false;
    }
}
