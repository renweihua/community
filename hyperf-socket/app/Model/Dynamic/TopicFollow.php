<?php

namespace App\Model\Dynamic;

use App\Model\Model;

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
