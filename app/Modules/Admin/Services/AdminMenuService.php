<?php

namespace App\Modules\Admin\Services;

use App\Models\Rabc\AdminMenu;

class AdminMenuService extends BaseService
{
    public function __construct(AdminMenu $adminMenu)
    {
        $this->model = $adminMenu;
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
