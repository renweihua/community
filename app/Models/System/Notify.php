<?php

namespace App\Models\System;

use App\Models\MonthModel;
use Illuminate\Support\Facades\DB;

class Notify extends MonthModel
{
    protected $primaryKey = 'notify_id';

    // 消息类型
    const NOTIFY_TYPE = [
        'SYSTEM_MSG' => 0, // 系统消息
        'INTERACT_MSG' => 1, // 互动消息
    ];

    // 目标类型
    const TARGET_TYPE = [
        'REGISTER' => 0, // 注册成功
        'DYNAMIC' => 1, // 动态
        'FOLLOW' => 2, // 关注
    ];

    // 互动消息的类型，目标类型：动态
    const DYNAMIC_TARGET_TYPE = [
        'PRAISE' => 0, // 点赞
        'COLLECTION' => 1, // 收藏
        'COMMENT' => 2, // 评论
        'REPLY_COMMENT' => 3, // 回复
    ];

    // 发送者类型:系统通知
    const SYSTEM_SENDER = 0;

    public function setNotifyContentAttribute($key)
    {
        $this->attributes['notify_content'] = empty($key) ? '' : $key;
    }

    public static function insert($data)
    {
        if (!isset($data['notify_content'])) $data['notify_content'] = '';
        return self::create($data);
    }

    /**
     * 获取未读消息的总量
     *
     * @param  int     $user_id
     * @param  array   $where
     * @param  string  $month_table
     * @param  int     $count
     *
     * @return int
     */
    public function getUnreadNums(int $user_id, array $where = [], string $month_table = '', int $count = 0)
    {
        $date_format = 'Y-m';
        if (!empty($month_table)){
            $month_table = date($date_format, strtotime($month_table));
        }else{
            $month_table = date($date_format);
        }

        if ( date(MonthModel::MONTH_FORMAT, strtotime($month_table)) >= MonthModel::MIN_TABLE ) {
            $this->setMonthTable($month_table);
            // 检测表名是否存在
            if ( !DB::select('SHOW TABLES LIKE "' . env('DB_PREFIX') . $this->getTable() . '"') ) {
                return $count;
            }
            $count += $this->where('is_read', 0)->where('user_id', $user_id)->where($where)->count();
            return $this->getUnreadNums($user_id, $where, date($date_format, strtotime('-1 month', strtotime($month_table))), $count);
        }else{
            return $count;
        }
    }
}
