<?php

declare(strict_types = 1);

namespace App\Model\Bbs;

use App\Model\Model;

class FriendGroup extends Model
{
    protected $pk        = 'group_id';

    /**
     * 获取指定会员的好友分组
     *
     * @param  int  $user_id
     *
     * @return array
     */
    public static function getGroups(int $user_id) : array
    {
        $list = self::getInstance()->cnpscyWhere('user_id', $user_id)->select('group_name', 'group_id')->get();
        $groups[0] = '我的好友';
        foreach ($list as $v) {
            $groups[$v->group_id] = $v->group_name;
        }
        return $groups;
    }
}