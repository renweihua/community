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
        // 互动消息：未读
        $interact_unreads = $notifyInstance->getUnreadNums($login_user_id, ['notify_type' => Notify::NOTIFY_TYPE['INTERACT_MSG']]);

        return compact('system_unreads', 'interact_unreads');
    }
}
