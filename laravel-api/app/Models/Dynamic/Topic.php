<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\TopicFactory;

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
