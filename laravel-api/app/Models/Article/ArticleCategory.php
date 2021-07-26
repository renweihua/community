<?php

namespace App\Models\Article;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Article\ArticleCategory
 *
 * @property int $category_id
 * @property string $category_name 分类名称
 * @property int $parent_id 父级Id
 * @property int $category_sort 排序
 * @property int $is_check 是否可用：1：可用；0：禁用
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereCategorySort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ArticleCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $is_delete  = 0;
    protected $cache_key  = 'article_categories';

    public function getSelectLists()
    {
        return $this->getCacheTree();
    }

    /**
     * 删除缓存
     *
     * @return bool
     */
    public function delCache()
    {
        Cache::forget($this->cache_key);
        return true;
    }

    /**
     * 获取文章分类的数据缓存
     *
     * @param  string  $get_type
     *
     * @return mixed
     */
    private function getCache(string $get_type = '')
    {
        return Cache::remember($this->cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() use ($get_type) {
            $all = $this->orderBy('category_sort', 'ASC')->get()->toArray();
            $tree = list_to_tree($all, $this->getKeyName());
            return compact('all', 'tree');
        });
    }

    /**
     * 获取Tree格式化数据
     *
     * @return array|mixed
     */
    public function getCacheTree()
    {
        return $this->getCache()['tree'] ?? [];
    }

    /**
     * 获取所有文章数据
     *
     * @return array|mixed
     */
    public function getCacheAll()
    {
        return $this->getCache()['all'] ?? [];
    }

    /**
     * 获取所有子集的Id集合（包含自己）
     *
     * @param  int    $parent_id
     * @param  array  $all
     *
     * @return array|int[]
     */
    public function getChildIds(int $parent_id, array $all = [])
    {
        $ids = [$parent_id];
        empty($all) && $all = $this->getCacheAll();
        foreach ($all as $key => $value) {
            if ( $value['parent_id'] == $parent_id ) {
                unset($all[$key]);
                $child_ids = $this->getChildIds((int)$value['category_id'], $all);
                !empty($child_ids) && $ids = array_merge($ids, $child_ids);
            }
        }
        return $ids;
    }
}
