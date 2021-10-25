<?php

namespace App\Models\Douyin;

use App\Models\Model;

/**
 * App\Models\Douyin\DouyinVideo
 *
 * @property int $video_id Id
 * @property int $author_id 作者Id
 * @property string $aweme_id 视频Id
 * @property string $cover 封面图
 * @property string $desc 描述
 * @property array|null $images 多图
 * @property array|null $video 视频信息
 * @property array|null $statistics 视频统计信息
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereAwemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereStatistics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinVideo whereVideoId($value)
 * @mixin \Eloquent
 */
class DouyinVideo extends Model
{
    protected $primaryKey = 'video_id';
    protected $is_delete = 0;
    protected $casts = [
        'images' => 'array', // 多图
        'video' => 'array', // 视频信息
        'statistics' => 'array', // 视频统计信息
    ];

    public function author()
    {
        return $this->belongsTo(DouyinAuthor::class, 'author_id', 'author_id');
    }
}
