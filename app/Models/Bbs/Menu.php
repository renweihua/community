<?php

namespace App\Models\Bbs;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    protected $primaryKey = 'menu_id';
    protected $is_delete = 0;

    /**
     * 只查询 启用 的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShow($query)
    {
        return $query->where('is_show', 1);
    }

    /**
     * 获取前端菜单栏目的缓存
     *
     * @param bool $is_tree
     * @param bool $force_update 是否强制更新缓存
     * @return array|mixed
     */
    public static function getMenusByWeb(bool $is_tree = true, bool $force_update = false)
    {
        $cache_key = CacheKeys::CACHE_WEB_MENUS;
        // 强制更新缓存
        if ($force_update){
            // 删除缓存key
            Cache::forget($cache_key);
        }
        $menus = Cache::remember($cache_key . $is_tree, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::show()->orderBy('menu_sort', 'ASC')->get()->toArray();
        });
        return $is_tree == true ? list_to_tree($menus) : $menus;
    }

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

    public function getMenuByUrl(string $menu_url)
    {
        return $this->where('menu_url', $menu_url)->first();
    }

    /**
     * 获取指定菜单的所有子分类Id
     *
     * @param int $parent_id
     * @param array $all
     * @return array|int[]
     */
    public static function getMenusChilds($parent_id = 0, $all = []): array
    {
        $arrIds = [$parent_id];
        empty($menus) && $menus = array_column(self::getMenusByWeb(false), null, 'menu_id');
        foreach ($menus as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                unset($all[$key]);
                $subIds = self::getMenusChilds($item['menu_id'], $menus);
                !empty($subIds) && $arrIds = array_merge($arrIds, $subIds);
            }
        }
        return $arrIds;
    }

    /**
     * 通过菜单Id获取所有上级菜单
     *
     * @param  int    $menu_id
     * @param  bool   $has_self
     * @param  array  $list_menus
     * @param  array  $cache_menus
     *
     * @return array
     */
    public static function getLocation(int $menu_id = 0, bool $has_self = true, $list_menus = [], $cache_menus = [], $self_id = 0): array
    {
        if (!$has_self && $self_id == 0) $self_id = $menu_id;
        $cache_menus || $cache_menus = array_column(self::getMenusByWeb(false), null, 'menu_id');
        if ($menu_id && $localtion_menu = $cache_menus[$menu_id]) {
            $list_menus[$menu_id] = $localtion_menu;
            $list_menus = self::getLocation($localtion_menu['parent_id'], $has_self, $list_menus, $cache_menus, $self_id);
        }
        if (!$has_self) unset($list_menus[$self_id]);
        ksort($list_menus);
        $list_menus = array_values($list_menus);
        return $list_menus;
    }
}
