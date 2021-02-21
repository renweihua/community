<?php

namespace App\Modules\Bbs\Services\User;

use App\Models\System\Notify;
use App\Services\Service;

class NotifyService extends Service
{
    /**
     * 获取我的未读消息
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function unread(int $login_user_id)
    {
        $notifyInstance = Notify::getInstance();

        // 系统消息：未读
        $system_unreads = $notifyInstance->getUnreadNums($login_user_id, ['notify_type' => Notify::NOTIFY_TYPE['SYSTEM_MSG']]);

        // 互动消息：点赞、评论（前端页面是分开的）
        $praise_unreads = $notifyInstance->getUnreadNums($login_user_id, ['notify_type' => Notify::NOTIFY_TYPE['INTERACT_MSG'], 'target_type'  => Notify::TARGET_TYPE['DYNAMIC'], 'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['PRAISE']]);

        $comment_unreads = $notifyInstance->getUnreadNums($login_user_id, function($query){
            $query->where(['notify_type' => Notify::NOTIFY_TYPE['INTERACT_MSG'], 'target_type'  => Notify::TARGET_TYPE['DYNAMIC']])->where(function($query){
                $query->where('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['COMMENT'])->whereOr('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['REPLY_COMMENT']);
            });
        });

        return compact('system_unreads', 'praise_unreads', 'comment_unreads');
    }
}
