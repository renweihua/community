<?php

namespace App\Model\Dynamic;

use App\Model\Model;
use Hyperf\Database\Model\Events\Created;
use Hyperf\Database\Model\Events\Deleted;

class DynamicCollection extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    /**
     * 模型事件
     */

    public function created(Created $event)
    {
        // 新增与删除收藏时，调用动态的统计缓存字段
        $this->dynamic->refreshCache();
    }

    public function deleted(Deleted $event)
    {
        // 新增与删除收藏时，调用动态的统计缓存字段
        $this->dynamic->refreshCache();
    }




    public function dynamic()
    {
        return $this->hasOne(Dynamic::class, 'dynamic_id', 'dynamic_id');
    }

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
