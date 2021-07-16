<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\UserInfo;

class DynamicComment extends Model
{
    protected $primaryKey = 'comment_id';
    protected $is_delete = 0;
    protected $appends = ['comment_time'];

    public function dynamic()
    {
        return $this->belongsTo(Dynamic::class, 'dynamic_id', 'dynamic_id')->where('is_delete', 0);
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    public function replyUser()
    {
        return $this->belongsTo(UserInfo::class, 'reply_user', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(DynamicComment::class, 'top_level', $this->primaryKey);
    }

    public function hasPraise()
    {
        return $this->hasOne(DynamicCommentPraise::class, $this->primaryKey, $this->primaryKey);
    }

    public function getCommentTimeAttribute($key)
    {
        return formatting_timestamp($this->attributes[self::CREATED_AT]);
    }
}
