<?php

namespace App\Models\Rabc;

use App\Models\Model;

class AdminRoleWithMenu extends Model
{
    protected $primaryKey = 'with_id';
    public $timestamps = false;

    public function getMenuIdsByRoles(array $role_ids)
    {
        return array_unique(array_column($this->whereIn('role_id', $role_ids)->select('menu_id')->get()->toArray(), 'menu_id'));
    }
}
