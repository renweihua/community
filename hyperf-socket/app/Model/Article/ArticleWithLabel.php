<?php

namespace App\Model\Article;

use App\Model\Model;

/**
 * App\Model\Article\ArticleWithLabel
 *
 * @property int $with_id
 * @property int $label_id 文章标签Id
 * @property int $article_id 文章Id
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel whereLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleWithLabel whereWithId($value)
 * @mixin \Eloquent
 */
class ArticleWithLabel extends Model
{
    protected $primaryKey = 'with_id';
    public $timestamps = false;
}
