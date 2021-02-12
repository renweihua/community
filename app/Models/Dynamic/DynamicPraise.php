<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\UserInfo;

class DynamicPraise extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    /**
     * 指定会员是否已【点赞】了指定动态
     *
     * @param  int  $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function isPraise(int $login_user, int $dynamic_id): bool
    {
        return $this->where([
            'user_id' => $login_user,
            'dynamic_id' => $dynamic_id,
        ])->first() ? true : false;
    }
}
