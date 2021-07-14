<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\User;
use App\Models\User\UserInfo;

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
