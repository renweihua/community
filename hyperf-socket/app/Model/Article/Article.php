<?php

namespace App\Model\Article;

use App\Constants\CacheKeys;
use App\Model\Bbs\Menu;
use App\Model\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * App\Model\Article\Article
 *
 * @property int $article_id 文章表
 * @property int $user_id 会员Id
 * @property int $menu_id 菜单Id
 * @property string $article_title 文章标题
 * @property string $article_keywords 关键词
 * @property string $article_description 描述
 * @property int $article_sort 排序
 * @property int $set_top 置顶
 * @property int $is_recommend 推荐
 * @property int $is_public 是否公开：0：私密；1：是；2.密码访问
 * @property string $access_password 访问密码
 * @property string $article_origin 文章来源
 * @property string $article_author 文章作者
 * @property string $article_link 详情外链
 * @property int $read_num 阅读数量
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property string $created_ip 创建时的IP
 * @property int $praise_count 点赞数量
 * @property int $collection_count 收藏数量
 * @property int $comment_count 评论数量
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_check 是否启用：0.否；1.是
 * @property false|string[] $article_images 多图
 * @property-read \App\Model\Article\ArticleCategory|null $category
 * @property-read \App\Model\Article\ArticleContent|null $content
 * @property-read string $article_cover
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Article\ArticleLabel[] $labels
 * @property-read int|null $labels_count
 * @property-read Menu $menu
 * @method static \Illuminate\Database\Eloquent\Builder|Article check()
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAccessPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCollectionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsRecommend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePraiseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSetTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    protected $primaryKey = 'article_id';
    protected $is_delete  = 0;
    // 追加封面图
    protected $appends = ['article_cover'];

    /**
     * 只查询 启用 的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheck($query)
    {
        return $query->where('is_check', 1);
    }

    /**
     * 获取文章的图片
     *
     * @param $key
     *
     * @return string
     */
    public function getArticleCoverAttribute($key)
    {
        if (empty($this->attributes['article_images'])) return '';
        $imgs = explode(',', $this->attributes['article_images']);
        return Storage::url(current($imgs));
    }

    /**
     * 获取多图，自动转成数组
     *
     * @param $key
     * @return false|string[]
     */
    public function getArticleImagesAttribute($key)
    {
        if ( empty($key) ) return [];
        $imgs = explode(',', $key);
        foreach ($imgs as &$img){
            $img = Storage::url($img);
        }
        return $imgs;
    }

    /**
     * 设置文章的图片
     *
     * @param $key
     */
    public function setArticleImagesAttribute($key)
    {
        if ( !empty($key) ) {
            foreach ($key as &$value){
                $value = str_replace(Storage::url('/'), '', $value);
            }
            $this->attributes['article_images'] = implode(',', $key);
        }
    }

    public function category()
    {
        return $this->hasOne(ArticleCategory::class, 'category_id', 'category_id');
    }

    public function labels()
    {
        return $this->belongsToMany(ArticleLabel::class, ArticleWithLabel::class, 'article_id', 'label_id', 'article_id', 'label_id')->withPivot([
            'article_id',
            'label_id',
        ]);
    }

    public function content()
    {
        $primaryKey = $this->getKeyName();
        return $this->hasOne(ArticleContent::class, $primaryKey, $primaryKey);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * 获取有效文章总量的统计
     *
     * @param  bool  $force_update 是否强制更新缓存
     *
     * @return int
     */
    public function cacheStatistics(bool $force_update = false) : int
    {
        $cache_key = CacheKeys::CACHE_ARTICLE_COUNT;
        // 强制更新缓存
        if ($force_update){
            // 删除缓存key
            Cache::forget($cache_key);
        }
        $articles_count = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return $this->check()->count();
        });
        return $articles_count;
    }
}
