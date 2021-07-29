<?php

namespace App\Models\Rabc;

use App\Models\Model;

/**
 * App\Models\Rabc\AdminMenu
 *
 * @property int $menu_id 菜单栏目表
 * @property int $parent_id 父级id
 * @property string $menu_name 栏目名称
 * @property string $vue_name
 * @property string $vue_path VUE路由路径
 * @property string $vue_redirect Vue的redirect
 * @property string $vue_icon 图标
 * @property string $vue_component VUE文件路径
 * @property string $vue_meta
 * @property string $external_links 外链
 * @property string $api_url 接口路由
 * @property string $api_method 接口的请求方式
 * @property int $menu_sort 排序
 * @property int $is_hidden 是否隐藏菜单栏：1：是；0：否
 * @property int $is_check 是否可用：1：可用；0：禁用
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereApiMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereApiUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereExternalLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereMenuSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVueComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVueIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVueMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVueName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVuePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu whereVueRedirect($value)
 * @mixin \Eloquent
 */
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
        return list_to_tree($this->select(['menu_id', 'menu_name', 'parent_id'])->orderBy('menu_sort', 'ASC')->get()->toArray());
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
