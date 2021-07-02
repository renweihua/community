<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class TopicFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    // 名称筛选
    public function name(string $topic_name = '')
    {
        if ( $topic_name ) {
            $this->where('topic_name', 'LIKE', "%{$topic_name}%");
        }
    }
}
