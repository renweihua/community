<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\User;
use App\Models\User\UserInfo;

class DynamicPraise extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // 新增与删除点赞时，调用动态的统计缓存字段
        $saveContent = function (self $dynamicPraise) {
            $dynamicPraise->dynamic->refreshCache();
        };

        static::created($saveContent);

        static::deleting($saveContent);
    }

    public function dynamic()
    {
        return $this->belongsTo(Dynamic::class, 'dynamic_id', 'dynamic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

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
