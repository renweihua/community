<?php

namespace App\Modules\Admin\Http\Controllers\Bbs;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Bbs\MenuRequest;
use App\Modules\Admin\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    public function __construct(MenuService $menuService)
    {
        $this->service = $menuService;
    }

    public function create(MenuRequest $request)
    {
        return $this->createService($request);
    }

    public function update(MenuRequest $request)
    {
        return $this->updateService($request);
    }

    public function getTplTypeAndViews(Request $request)
    {
        // 获取所有视图文件列表
        $view_lists = getViewsByResource(config('modules.paths.modules') . '/Bbs/Resources/views');

        return $this->successJson([
            'menu_type_list' => cnpscy_config('menu_type_list'),
            'view_lists' => $view_lists,
        ]);
    }
}
