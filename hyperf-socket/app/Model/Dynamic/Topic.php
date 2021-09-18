<?php

namespace App\Model\Dynamic;

use App\Model\Model;

class Topic extends Model
{
    protected $primaryKey = 'topic_id';
    protected $is_delete = 0;

    public function isFollow()
    {
        return $this->hasOne(TopicFollow::class, $this->primaryKey, $this->primaryKey);
    }

    public static function getListByIds(array $ids)
    {
        $list = self::whereIn('topic_id', $ids)->select('topic_id', 'topic_name')->get()->toArray();
        return array_column($list, null, 'topic_id');
    }

    /**
     * 获取默认的话题Id
     *
     * @return int
     */
    public static function getDetaultTopicId(): int
    {
        return self::where('is_default', 1)->value('topic_id') ?? 0;
    }
}
