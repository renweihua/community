<?php

namespace App\Models\Douyin;

use App\Models\Model;

/**
 * App\Models\Douyin\DouyinAuthor
 *
 * @property int $author_id Id
 * @property string $sec_uid
 * @property string $uid
 * @property string $unique_id
 * @property string $nick_name 昵称
 * @property string $avatar_thumb 头像
 * @property string $share_url 抖音作者分享的URL
 * @property int $total_favorited
 * @property int $follower_count 关注数量
 * @property string $signature 签名
 * @property int $is_delete 是否删除
 * @property int $last_sync 上一次同步的时间
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property array|null $original_author 作者原始数据
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereAvatarThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereFollowerCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereLastSync($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereOriginalAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereSecUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereShareUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereTotalFavorited($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DouyinAuthor whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class DouyinAuthor extends Model
{
    protected $primaryKey = 'author_id';
    protected $is_delete = 0;

    protected $casts = [
        'original_author' => 'array', // 作者原始数据
    ];
}
