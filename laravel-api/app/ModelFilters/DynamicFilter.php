<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class DynamicFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function tab($tab)
    {
        $this->check();

        switch ($tab) {
            case 'default':
                $this->latest('dynamic_id');

                break;
            case 'featured':
                // $this->whereNotNull('excellent_at')->latest('excellent_at');

                break;
            case 'recent':
                $this->latest()->latest('updated_time');
                break;
            case 'zeroComment': // 零评论
                $this->doesntHave('comments')->latest();
                break;
        }
    }

    // 会员筛选
    public function user(int $user_id = -1)
    {
        if ($user_id){
            $this->where('user_id', $user_id);
        }
    }

    // 话题筛选
    public function topic(int $topic_id = -1)
    {
        if ($topic_id){
            $this->where('topic_id', $topic_id);
        }
    }

    // 类型筛选
    public function type(int $dynamic_type = -1)
    {
        if ($dynamic_type){
            $this->where('dynamic_type', $dynamic_type);
        }
    }
}
