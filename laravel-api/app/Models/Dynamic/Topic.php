<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\TopicFactory;

/**
 * App\Models\Dynamic\Topic
 *
 * @property int $topic_id 话题 表
 * @property string $topic_name 名称
 * @property string $topic_description 描述
 * @property string $topic_cover 图标/封面
 * @property int $topic_sort 排序
 * @property int $dynamic_count 动态数量
 * @property int $follow_count 关注人数
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除
 * @property-read \App\Models\Dynamic\TopicFollow|null $isFollow
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereDynamicCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereFollowCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopicCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopicDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopicSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Topic extends Model
{
    protected $primaryKey = 'topic_id';
    protected $is_delete = 0;

    protected static function newFactory()
    {
        return TopicFactory::new();
    }

    public function isFollow()
    {
        return $this->hasOne(TopicFollow::class, $this->primaryKey, $this->primaryKey);
    }
}
