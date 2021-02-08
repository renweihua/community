<?php

namespace App\Models\Rabc;

use App\Models\Model;

class AdminMenu extends Model
{
    protected $primaryKey = 'menu_id';
    protected $is_delete = 0;

    public function getAllMenus()
    {
        return $this->orderBy('menu_sort', 'ASC')->get();
    }

    public function getSelectLists()
    {
        return list_to_tree($this->orderBy('menu_sort', 'ASC')->get()->toArray());
    }

    public function getMenusByIds(array $menu_ids)
    {
        return $this->whereIn('menu_id', $menu_ids)
            ->orderBy('menu_sort', 'ASC')
            ->get();
    }

    public function getMenusByIdsForRabc(array $menu_ids)
    {
        return $this->whereIn('menu_id', $menu_ids)->where('api_url', '<>', '')->pluck('api_method', 'api_url');
    }
}
