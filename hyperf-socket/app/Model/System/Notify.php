<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Model\MonthModel;
use App\Model\User\UserInfo;
use Hyperf\DbConnection\Db;

class Notify extends MonthModel
{
    protected $primaryKey = 'notify_id';

    public function user()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }
    protected $appends = ['time_formatting'];

    // 时间戳格式化
    public function getTimeFormattingAttribute($value)
    {
        return formatting_timestamp($this->attributes['created_time']);
    }

    // 消息类型
    const NOTIFY_TYPE = [
        'SYSTEM_MSG' => 0, // 系统消息
        'INTERACT_MSG' => 1, // 互动消息
    ];

    // 目标类型
    const TARGET_TYPE = [
        'REGISTER' => 0, // 注册成功
        'DYNAMIC' => 1, // 动态
        'FOLLOW' => 2, // 关注会员
        'SUBSCRIBE' => 3, // 订阅话题
    ];

    // 互动消息的类型，目标类型：动态
    const DYNAMIC_TARGET_TYPE = [
        'PRAISE' => 0, // 点赞
        'COLLECTION' => 1, // 收藏
        'COMMENT' => 2, // 评论
        'REPLY_COMMENT' => 3, // 回复
        'DELETE_COMMENT' => 4, // 删除评论
        'COMMENT_PRAISE' => 5, // 点赞评论
        'SHARE' => 6, // 分享动态
    ];

    // 发送者类型:系统通知
    const SYSTEM_SENDER = 0;

    public function setNotifyContentAttribute($key)
    {
        $this->attributes['notify_content'] = empty($key) ? '' : $key;
    }

    public function sender()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'sender_id')->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_uuid');
    }

    public static function insert($data)
    {
        if (!isset($data['notify_content'])) $data['notify_content'] = '';
        // 如果存在数据，那么不做录入
        if(!self::where($data)->first()) return self::create($data);
        return true;
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
    public function getUnreadNums(int $user_id, $where = [], string $month_table = '', int $count = 0)
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
            if ( !Db::select('SHOW TABLES LIKE "' . get_db_prefix() . $this->getTable() . '"') ) {
                return $count;
            }
            $count += $this->where('is_read', 0)->where('user_id', $user_id)->where($where)->count();
            return $this->getUnreadNums($user_id, $where, date($date_format, strtotime('-1 month', strtotime($month_table))), $count);
        }else{
            return $count;
        }
    }

    public function setExplain(&$v)
    {
        $v->explain = '';
        switch ($v->target_type){
            case self::TARGET_TYPE['DYNAMIC']: // 动态
                switch ($v->notify_type){
                    case 0:
                        $v->explain = $v->notify_content;
                        break;
                    default:
                        switch ($v->dynamic_type){
                            case self::DYNAMIC_TARGET_TYPE['PRAISE']: // 点赞
                                $v->explain = '点赞了您的动态';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['COLLECTION']: // 收藏
                                $v->explain = '喜欢了您的动态';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['COMMENT']: // 评论
                                $v->explain = '评论了您的动态';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['SHARE']: // 分享动态
                                $v->explain = '分享了您的动态';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['COMMENT_PRAISE']: // 点赞评论
                                $v->explain = '喜欢了您的评论';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['REPLY_COMMENT']:
                                $v->explain = '回复了您的评论';
                                break;
                            case self::DYNAMIC_TARGET_TYPE['DELETE']:
                                $v->explain = '您有一个动态被管理员删除';
                                break;
                        }
                        break;
                }
                break;
            case self::TARGET_TYPE['FOLLOW']: // 关注
                $v->explain = '关注了您';
                break;
            default: //
                $v->explain = $v->notify_content;
                break;
        }
    }
}