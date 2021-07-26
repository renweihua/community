<?php

namespace App\Models\Bbs;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Bbs\Menu
 *
 * @property int $menu_id 前台栏目表
 * @property int $parent_id 父级id
 * @property string $menu_name 栏目名称
 * @property int $menu_tpltype 模板类型
 * @property string $menu_listtpl 列表页模板
 * @property string $menu_detailtpl 详情模板
 * @property string $menu_icon 图标
 * @property string $menu_url 路由
 * @property string $menu_cover 单页缩略图
 * @property string|null $menu_content 单页内容内容
 * @property string $menu_keywords head头部展示的关键字搜索
 * @property string $menu_description head头部展示的描述
 * @property int $menu_sort 排序
 * @property int $is_show 是否展示在首页：1：展示；0：隐藏
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu show()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuDetailtpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuListtpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuTpltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedTime($value)
 * @mixin \Eloquent
 */
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
