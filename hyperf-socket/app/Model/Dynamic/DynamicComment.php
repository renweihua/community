<?php

namespace App\Model\Dynamic;

use App\Model\Model;
use App\Model\User\UserInfo;
use Hyperf\Database\Model\Events\Created;
use Hyperf\Database\Model\Events\Deleted;

class DynamicComment extends Model
{
    protected $primaryKey = 'comment_id';
    protected $is_delete  = 0;
    protected $appends = ['comment_time'];

    public function created(Created $event)
    {
        // 新增与删除评论时，调用动态的统计缓存字段
        $this->dynamic->refreshCache();
    }

    public function deleted(Deleted $event)
    {
        // 新增与删除评论时，调用动态的统计缓存字段
        $this->dynamic->refreshCache();
    }

    public function saving(Deleted $event)
    {
        if ($this->isDirty('comment_markdown') && !empty($this->comment_markdown)) {
            $this->comment_content = self::toHTML($this->comment_markdown);
        }
    }

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

    public static function toHTML(string $markdown)
    {
        return app(\ParsedownExtra::class)->text(\emoji($markdown));
    }

    public static function getListByIds(array $ids)
    {
        $list = self::whereIn('comment_id', $ids)->select('comment_id', 'comment_content', 'created_time')->get()->toArray();
        return array_column($list, null, 'comment_id');
    }
}
