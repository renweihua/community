<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function limit($count)
    {
        $this->take($count);
    }

    // 筛选
    public function name(string $search = '')
    {
        if ( $search ) {
            return $this->where(function($q) use ($search)
            {
                return $q->where('user_mobile', 'LIKE', "%$search%")
                    ->orWhere('user_name', 'LIKE', "%$search%")
                    ->orWhere('user_email', 'LIKE', "%$search%");
            });
        }
        return $this;
    }

    public function latest()
    {
        $this->orderByDesc('user_id');
    }
}
