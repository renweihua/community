<?php

namespace App\Models\System;

use App\Models\MonthModel;
use App\Models\User\UserInfo;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\System\Notify
 *
 * @property int $notify_id 系统消息通知记录表
 * @property int $user_id 会员Id
 * @property int $is_read 是否已读：1：是；0：否
 * @property int $notify_type 通知类型：0.系统通知/公告；1.提醒；2.私信
 * @property int $target_id 目标Id(比如动态ID)
 * @property int $target_type 目标类型：0.动态
 * @property int $sender_id 发送者Id
 * @property int $sender_type 发送者类型：0.系统通知；1.指定会员
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string|null $notify_content 通知内容
 * @property int $dynamic_type 动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除
 * @property int $admin_id 管理员Id
 * @property-read UserInfo|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereDynamicType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereNotifyContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereNotifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereNotifyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notify whereUserId($value)
 * @mixin \Eloquent
 */
class Notify extends MonthModel
{
    protected $primaryKey = 'notify_id';
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
        'FOLLOW' => 2, // 关注
    ];

    // 互动消息的类型，目标类型：动态
    const DYNAMIC_TARGET_TYPE = [
        'PRAISE' => 0, // 点赞
        'COLLECTION' => 1, // 收藏
        'COMMENT' => 2, // 评论
        'REPLY_COMMENT' => 3, // 回复
        'DELETE_COMMENT' => 4, // 删除评论
        'COMMENT_PRAISE' => 5, // 点赞评论
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
            if ( !DB::select('SHOW TABLES LIKE "' . env('DB_PREFIX') . $this->getTable() . '"') ) {
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
