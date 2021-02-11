<?php

namespace App\Models\Dynamic;

use App\Models\Model;

class DynamicCollection extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    /**
     * 指定会员是否已【收藏】了指定动态
     *
     * @param  int  $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function isCollection(int $login_user, int $dynamic_id):bool
    {
        return $this->where([
            'user_id' => $login_user,
            'dynamic_id' => $dynamic_id,
        ])->first() ? true : false;
    }
}
