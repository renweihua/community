<?php

namespace App\Modules\Admin\Services;

use App\Models\Bbs\Menu;

class MenuService extends BaseService
{
    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    /**
     * 菜单列表
     *
     * @param array $params
     * @return array
     */
    public function lists(array $params) : array
    {
        $lists = $this->model->orderBy('menu_sort', 'ASC')->get();

        return list_to_tree($lists->toArray());
    }

    public function getSelectLists($request)
    {
        return $this->model->getSelectLists();
    }
}
