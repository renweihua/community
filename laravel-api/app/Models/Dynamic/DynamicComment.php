<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\UserInfo;

/**
 * App\Models\Dynamic\DynamicComment
 *
 * @property int $comment_id 动态评论回复表
 * @property int $user_id 会员Id
 * @property int $reply_user 会员Id
 * @property int $dynamic_id 动态Id
 * @property int $author_id 作者Id
 * @property string|null $comment_content 回复内容
 * @property int $top_level 顶级的Id（顶级上一级的reply_id = 0）
 * @property int $reply_id 回复评论的Id
 * @property int $is_read 是否已读：0：否；1：是
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string|null $comment_markdown 回复内容
 * @property string $content_type 内容的格式：html；markdown
 * @property int $praise_count 点赞量
 * @property-read \App\Models\Dynamic\Dynamic $dynamic
 * @property-read mixed $comment_time
 * @property-read \App\Models\Dynamic\DynamicCommentPraise|null $hasPraise
 * @property-read \Illuminate\Database\Eloquent\Collection|DynamicComment[] $replies
 * @property-read int|null $replies_count
 * @property-read UserInfo $replyUser
 * @property-read UserInfo $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereCommentContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereCommentMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereDynamicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment wherePraiseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereReplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereReplyUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereTopLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicComment whereUserId($value)
 * @mixin \Eloquent
 */
class DynamicComment extends Model
{
    protected $primaryKey = 'comment_id';
    protected $is_delete = 0;
    protected $appends = ['comment_time'];

    protected static function boot()
    {
        parent::boot();

        // 新增与删除评论时，调用动态的统计缓存字段
        $saveContent = function (self $dynamicPraise) {
            $dynamicPraise->dynamic->refreshCache();
        };

        static::created($saveContent);

        static::deleted($saveContent);

        static::saving(function ($content) {
            if ($content->isDirty('comment_markdown') && !empty($content->comment_markdown)) {
                $content->comment_content = self::toHTML($content->comment_markdown);
            }

            // $content->body = Purifier::clean($content->body);
        });
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
}
