<?php

namespace App\Modules\Bbs\Services\User;

use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicComment;
use App\Models\System\Notify;
use App\Models\User\UserInfo;
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

    /**
     * 我的所有消息通知
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getAllNotify(int $login_user_id)
    {
        return $this->getNotify($login_user_id);
    }

    /**
     * 我的{点赞}消息通知
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getPraiseByNotify(int $login_user_id)
    {
        return $this->getNotify($login_user_id, ['target_type'  => Notify::TARGET_TYPE['DYNAMIC'], 'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['PRAISE']]);
    }

    /**
     * 我的{提醒|系统}消息通知
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getSystemByNotify(int $login_user_id)
    {
        return $this->getNotify($login_user_id, ['notify_type'  => Notify::NOTIFY_TYPE['SYSTEM_MSG']]);
    }

    /**
     * 我的{评论}消息通知
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getCommentByNotify(int $login_user_id)
    {
        return $this->getNotify($login_user_id, function($query){
            $query->where(['notify_type' => Notify::NOTIFY_TYPE['INTERACT_MSG'], 'target_type'  => Notify::TARGET_TYPE['DYNAMIC']])->where(function($query){
                $query->where('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['COMMENT'])->whereOr('dynamic_type', Notify::DYNAMIC_TARGET_TYPE['REPLY_COMMENT']);
            });
        });
    }

    protected function getNotify(int $login_user_id, $where = [], $default_search_month = '')
    {
        if (empty($default_search_month)){
            $search_month = request()->input('search_month', '');
            // laravel 默认空数据会自动转换成Null
            $search_month = empty($search_month) ? '' : $search_month;
        }else $search_month = $default_search_month;

        $notifyInstance = Notify::getInstance();

        $paginates = $notifyInstance->setMonthTable($search_month)
            ->filter(request()->all())
            ->where('user_id', $login_user_id)
            ->where($where)
            ->with(['sender'])
            ->orderBy('notify_id', 'DESC')
            ->paginate($this->getLimit(request()->input('limit', 10)));

        /**
         * 消息数据格式处理
         */
        $user_ids = $dynamic_ids = $comment_ids = [];
        $user_infos = $dynamics = [];
        // 设置已读数量
        $set_read_nums = 0;
        foreach ($paginates as $item){
            switch ($item->target_type){
                case $notifyInstance::TARGET_TYPE['DYNAMIC']: // 动态
                    $dynamic_ids[] = $item->target_id;
                    // 评论动态
                    if(
                        $item->dynamic_type == $notifyInstance::DYNAMIC_TARGET_TYPE['COMMENT']
                        ||
                        $item->dynamic_type == $notifyInstance::DYNAMIC_TARGET_TYPE['COMMENT_PRAISE']
                    ) $comment_ids[] = $item->extend_id;
                    break;
                case $notifyInstance::TARGET_TYPE['FOLLOW']: // 关注
                    $user_ids[] = $item->sender_id;
                    break;
            }

            // 标记未读的记录：设置为已读状态
            if ($item->is_read == 0){
                $item->is_read = 1;
                $item->save();

                ++$set_read_nums;
            }
        }
        if (!empty($dynamic_ids)){
            $dynamics = Dynamic::getListByIds($dynamic_ids);
        }
        if (!empty($user_ids)){
            $user_infos = UserInfo::getListByIds($user_ids);
        }
        if (!empty($comment_ids)){
            $comment_infos = DynamicComment::getListByIds($comment_ids);
        }
        foreach ($paginates as $item){
            switch ($item->target_type){
                case $notifyInstance::TARGET_TYPE['DYNAMIC']: // 动态
                    $item->relation = (object)$dynamics[$item->target_id];
                    // 评论
                    if(
                        $item->dynamic_type == $notifyInstance::DYNAMIC_TARGET_TYPE['COMMENT']
                        ||
                        $item->dynamic_type == $notifyInstance::DYNAMIC_TARGET_TYPE['COMMENT_PRAISE']
                    ){
                        $item->comment = (object)($comment_infos[$item->extend_id] ?? []);
                    }
                    $notifyInstance->setExplain($item);
                    break;
                case $notifyInstance::TARGET_TYPE['FOLLOW']: // 关注
                    $item->relation = (object)$user_infos[$item->sender_id];
                    $item->explain = '关注了您';
                    break;
                default:
                    $item->relation = (object)[];
                    $item->explain = $item->notify_content;
                    break;
            }
        }


        $lists = $this->getPaginateFormat($paginates);

        /**
         * 是否还存在更多数据：
         *  获取最后一条数据的时间戳为查询月份
         */
        if (empty($lists['data'])) {
            // 大于最小月份时，继续查询
            if ( date(Notify::MONTH_FORMAT, strtotime($search_month)) > Notify::MIN_TABLE ) {
                $default_search_month = date('Y-m', strtotime('-1 month', strtotime($search_month)));
                return $this->getNotify($login_user_id, $where, $default_search_month);
            }
            $lists['month_table'] = $search_month;
        } else {
            $lists['month_table'] = date('Y-m', current($lists['data'])['created_time']);
        }

        $lists['set_read_nums'] = $set_read_nums;
        return $lists;
    }
}
