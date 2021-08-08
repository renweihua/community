<?php

namespace App\ModelFilters;

use App\Models\System\Notify;
use EloquentFilter\ModelFilter;

class NotifyFilter extends ModelFilter
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
        switch ($tab) {
            case 'follow': // 关注
                $this->where(['target_type'  => Notify::TARGET_TYPE['FOLLOW']]);
                break;
            case 'subscribe': // 订阅
                $this->where(['target_type'  => Notify::TARGET_TYPE['SUBSCRIBE']]);
                break;
            case 'comment': // 评论
                $this->where(['notify_type' => Notify::NOTIFY_TYPE['INTERACT_MSG'], 'target_type'  => Notify::TARGET_TYPE['DYNAMIC']])->where(function($query){
                    $query->where('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['COMMENT'])->whereOr('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['REPLY_COMMENT']);
                });
                break;
            case 'like': // 点赞
                $this->where(['target_type'  => Notify::TARGET_TYPE['DYNAMIC'], 'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['PRAISE']]);
                break;
            default:
                break;
        }
    }
}
