<?php
declare(strict_types = 1);

namespace App\Model\Bbs;

use App\Model\MonthModel;
use App\Model\User\UserInfo;
use Hyperf\DbConnection\Db;

class GroupChatRecord extends MonthModel
{
    protected $primaryKey = 'record_id';

    public function getChatContentAttribute($value)
    {
        return html_entity_decode(htmlspecialchars_decode($value));
    }

    public function setChatContentAttribute($value)
    {
        $this->attributes['chat_content'] = htmlspecialchars($value);
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    public function friendInfo()
    {
        return $this->belongsTo(UserInfo::class, 'friend_id', 'user_id');
    }

    /**
     * 获取与指定好友的历史聊天记录
     *
     * @param  int     $user_id
     * @param  int     $friend_id
     * @param  string  $month_table
     * @param  int     $limit
     *
     * @return array|bool
     */
    public function getHistory(int $user_id, int $friend_id, string $month_table = '', int $limit = 20)
    {
        // 设置搜索的月份表
        $this->setMonthTable($month_table);
        // 检测表名是否存在
        if (!Db::selectOne('SHOW TABLES LIKE "' . $this->getTableName() . '"')) {
            return false;
        }
        // 执行查询
        // 必须嵌套多层的 function，where与whereOr组合，最后的SQL语句也是存在问题
        $list = $this->cnpscyWhere(function ($query) use ($user_id, $friend_id) {
            $query->where(function ($query) use ($user_id, $friend_id) {
                $query->where(['user_id' => $user_id, 'friend_id' => $friend_id,]);
            })->orWhere(function ($query) use ($user_id, $friend_id) {
                $query->where(['user_id' => $friend_id, 'friend_id' => $user_id,]);
            });
        })->with([
            'userInfo' => function($query){
                $query->select('user_id', 'nick_name', 'user_sex', 'user_avatar');
            },
            'friendInfo' => function($query){
                $query->select('user_id', 'nick_name', 'user_sex', 'user_avatar');
            },
        ])->orderBy('created_time', 'DESC')->orderBy('created_time', 'DESC')->paginate($limit);
        return $this->setPaginate($list);
    }
}