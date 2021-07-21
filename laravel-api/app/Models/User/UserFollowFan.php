<?php

namespace App\Models\User;

use App\Models\Model;

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
