<?php

namespace App\Model\Article;

use App\Model\Model;

/**
 * App\Model\Article\ArticleContent
 *
 * @property int $article_id 文章Id
 * @property string|null $article_content 内容
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent whereArticleContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleContent whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @mixin \Eloquent
 */
class ArticleContent extends Model
{
    protected $primaryKey = 'article_id';
    public $timestamps = false;
}
