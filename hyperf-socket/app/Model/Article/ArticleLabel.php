<?php

namespace App\Model\Article;

use App\Constants\CacheKeys;
use App\Model\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Model\Article\ArticleLabel
 *
 * @property int $label_id
 * @property string $label_name 标签名
 * @property int $is_delete 是否删除：1：删除；0：正常
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Article\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel whereLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel whereLabelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleLabel whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ArticleLabel extends Model
{
    protected $primaryKey = 'label_id';
    protected $is_delete = 0;

    public function articles()
    {
        return $this->belongsToMany(Article::class,
            ArticleWithLabel::class,
            'label_id',
            'article_id',
            'label_id',
            'article_id')
            ->withPivot([
            'article_id',
            'label_id',
        ]);
    }

    /**
     * 标签缓存数据
     *
     * @return mixed
     */
    public static function getLabelsByCache()
    {
        return Cache::remember(CacheKeys::CACHE_WEB_LABELS, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::get();
        });
    }
}
