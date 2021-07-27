<?php

namespace App\Models\Dynamic;

use App\Models\Model;

/**
 * App\Models\Dynamic\TopicFollow
 *
 * @property int $relation_id 话题关注表
 * @property int $user_id 会员主键
 * @property int $topic_id 话题Id
 * @property int $created_time 创建时间
 * @property-read mixed $updated_time
 * @property-read \App\Models\Dynamic\Topic|null $topic
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicFollow whereUserId($value)
 * @mixin \Eloquent
 */
class TopicFollow extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    /**
     * 指定会员是否关注指定荟吧
     *
     * @param  int  $login_user
     * @param  int  $topic_id
     *
     * @return mixed
     */
    public function checkFollow(int $login_user, int $topic_id)
    {
        return $this->where('user_id', $login_user)->where('topic_id', $topic_id)->first();
    }

    public function topic()
    {
        return $this->hasOne(Topic::class, 'topic_id', $this->primaryKey);
    }
}
