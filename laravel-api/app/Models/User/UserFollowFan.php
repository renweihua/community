<?php

namespace App\Models\User;

use App\Models\Model;

/**
 * App\Models\User\UserFollowFan
 *
 * @property int $relation_id 会员关注与粉丝记录表
 * @property int $user_id 会员主键
 * @property int $friend_id 对应会员Id
 * @property int $cross_correlation 是否双方进行关注：0：否；1：是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property int $is_special 是否特别关心：1：是；0：否
 * @property int $is_blacklist 是否拉黑：1：是；0：否
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property-read \App\Models\User\UserInfo|null $friendInfo
 * @property-read \App\Models\User\UserInfo|null $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereCrossCorrelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereFriendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereIsBlacklist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFollowFan whereUserId($value)
 * @mixin \Eloquent
 */
class UserFollowFan extends Model
{
    protected $primaryKey = 'relation_id';

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }

    public function friendInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'friend_id');
    }

    /**
     * 会员，是否关注指定会员
     *
     * @param  int  $login_user
     * @param  int  $friend_id
     *
     * @return mixed
     */
    public function checkFollow(int $login_user, int $friend_id)
    {
        return $this->where('user_id', $login_user)->where('friend_id', $friend_id)->first();
    }

    /**
     * 指定会员组,检测指定会员是否已关注
     *
     * @param  int    $login_user
     * @param  array  $user_ids
     *
     * @return array
     */
    public function getCheckFollowByUserIds(int $login_user, array $user_ids):array
    {
        return $this->where('user_id', $login_user)->whereIn('friend_id', $user_ids)->column('record_id', 'friend_id');
    }
}
