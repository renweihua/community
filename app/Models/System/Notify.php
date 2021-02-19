<?php

namespace App\Models\System;

use App\Models\MonthModel;

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
}
