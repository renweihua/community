<?php

namespace App\Model\Dynamic;

use App\Model\Model;
use App\Model\User\User;
use App\Model\User\UserInfo;

/**
 * App\Model\Dynamic\DynamicCommentPraise
 *
 * @property int $relation_id 动态点赞表
 * @property int $user_id 会员Id
 * @property int $dynamic_id 动态Id
 * @property int $comment_id 评论表
 * @property int $created_time 创建时间
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property-read mixed $updated_time
 * @property-read User $user
 * @property-read UserInfo $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereDynamicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCommentPraise whereUserId($value)
 * @mixin \Eloquent
 */
class DynamicCommentPraise extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    /**
     * 指定会员是否已【点赞】了指定评论
     *
     * @param  int  $login_user
     * @param  int  $comment_id
     *
     * @return bool
     */
    public function isPraise(int $login_user, int $comment_id): bool
    {
        return $this->where([
            'user_id' => $login_user,
            'comment_id' => $comment_id,
        ])->first() ? true : false;
    }
}
