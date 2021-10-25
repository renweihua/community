<?php

namespace App\Service\Socket;

use App\Model\Bbs\ChatRecord;
use App\Model\Bbs\GroupChatRecord;
use App\Service\Service;

/**
 * 聊天室服务层
 *
 * Class ChatService
 *
 * @package App\Service\Socket
 */
class ChatService extends Service
{
    // 私聊的记录：redis标识
    const PRIVATE_CHAT_RECORDS = 'privateChat-records';

    /**
     * 获取与指定好友的历史聊天记录
     *
     * @param $socket
     * @param $params
     *
     * @return array
     */
    public static function getPrivateChatRecords($sid, $params): array
    {
        $user_id = UserService::getUserIdBySid($sid);
        $friend_id = intval($params['friend_id'] ?? 0);
        $page = intval($params['page'] ?? 1);
        $month_table = empty($params['month_table']) ? date('Y-m') : $params['month_table'];
        // 获取历史消息记录，使用缓存数据
        // 获取消息记录
        $list = ChatRecord::getInstance()->getHistory($user_id, $friend_id, $month_table);
        if ($list === false) {
            return ['current_page' => 1, 'last_page' => 1, 'month_table' => $month_table, 'per_page' => 15, 'total' => 0, 'data' => [],];
        } else {
            $data = $list['data'];
            // 获取最后一条数据的当前月份
            if (empty($data)) {
                // 大于最小月份时，继续查询
                if ($month_table > ChatRecord::MIN_TABLE) {
                    $params['month_table'] = strtotime('-1 month', strtotime($month_table));
                    return self::getPrivateChatRecords($sid, $params);
                }
            } else {
                $list['month_table'] = date('Y-m', strtotime(current($data)['created_time']));
            }
        }
        // 按照创建时间来进行排序
        $sort_array = array_column($list['data'], 'created_time');
        array_multisort($sort_array, SORT_ASC, $list['data']);
        return $list;
    }

    public static function setPrivateChat($sid, $params)
    {
        $data['user_id'] = UserService::getUserIdBySid($sid);
        $data['friend_id'] = $params['friend_id'];
        $data['chat_type'] = $params['chat_type'] ?? 0; // 内容格式
        $data['chat_content'] = $params['content'];
        $data['is_read'] = 0;
        $result = ChatRecord::getInstance()->add($data);
        // 加载关联模型：发送者与接收者的基本信息
        $result->load('friendInfo', 'userInfo');
        return $result;
    }

    public static function setGroupChat($sid, $params)
    {
        $data['user_id'] = UserService::getUserIdBySid($sid);
        $data['group_id'] = $params['group_id'];
        $data['chat_type'] = $params['chat_type'] ?? 0; // 内容格式
        $data['chat_content'] = $params['content'];
        $result = GroupChatRecord::getInstance()->create($data);
        $result->load('userInfo');
        return $result;
    }
}