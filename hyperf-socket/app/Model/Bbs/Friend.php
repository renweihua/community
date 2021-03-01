<?php

namespace App\Model\Bbs;

use App\Exception\Exception;
use App\Library\ChineseCharactersByInitialGrouping;
use App\Model\Model;
use App\Model\User\UserInfo;

/**
 * 好友关系
 *
 * Class Friend
 *
 * @package App\Model\Bbs
 */
class Friend extends Model
{
    public $is_delete = 1;// 是否删除：0.假删除；1.真删除【默认全部假删除】

    // 关联分组模型
    public function group()
    {
        return $this->belongsTo(FriendGroup::class, 'group_id');
    }

    // 关联好友信息模型
    public function friend()
    {
        return $this->belongsTo(UserInfo::class, 'friend_id', 'user_id');
    }

    public static function checkFriend(int $user_id, int $friend_id) : bool
    {
        return self::getInstance()->cnpscyDetail(['user_id' => $user_id, 'friend_id' => $friend_id,]) ? true : false;
    }

    public static function getInfoByCheckFriend(int $user_id, int $friend_id)
    {
        return self::getInstance()->cnpscyDetail(['user_id' => $user_id, 'friend_id' => $friend_id,]);
    }

    /**
     * 获取好友的会员Id
     *
     * @param  int  $user_id
     * @param  int  $append_user  是否追加当前会员的Id
     *
     * @return string
     */
    public static function getMyfriendIds(int $user_id, int $append_user = 1) : string
    {
        $friends = self::getInstance()
                       ->cnpscyWhere('user_id', $user_id)
                       ->orderBy('friend_id', 'ASC')
                       ->pluck('friend_id')
                       ->toArray();

        $friend_ids = '';
        if ( !empty($friends) ) {
            $friend_ids = implode(',', $friends);
        }
        // 追加自己的Id
        if ( $append_user ) $friend_ids = empty($friend_ids) ? $user_id : $friend_ids . ',' . $user_id;
        return $friend_ids;
    }

    /**
     * 获取所有好友，并进行分组排序
     *
     * @param  int  $user_id
     *
     * @return array
     */
    public function getMyFriends(int $user_id) : array
    {
        /**
         * 按照微信首字母分组
         */
        $friends = $this->cnpscyWith(['friend.avatar'])->where('user_id', $user_id)->get();
        $list = [];
        if ( $friends ) {
            foreach ($friends as $key => &$friend) {
                // 如果会员信息不存在，那么unset
                if ( !$friend->friend ) unset($friends[$key]);
                // 如果有备注就展示备注，否则展示昵称
                $friend->initials_name = empty($friend->friend_remarks) ? $friend->friend->nick_name : $friend->friend_remarks;
            }
            // 按照首字母进行分组
            $data_list = ChineseCharactersByInitialGrouping::getInstance()
                                                           ->groupByInitials($friends->toArray(), 'initials_name');
            foreach ($data_list as $key => $value) {
                $list[] = ['initials_name' => $key, 'friends' => $value,];
            }
        }
        return $list;

        exit;
        /**
         * 按照QQ分组的模式
         */
        // 获取当前会员的所有分组
        $group_list = BbsFriendGroup::getGroups($user_id);

        $friends = $this->cnpscyWith(['friend.avatar'])->where('user_id', $user_id)->get();

        $group_friends = [];
        // 好友分组
        if ( !empty($group_list) && !empty($friends) ) {
            foreach ($group_list as $group_id => $group) {
                if ( !isset($group_friends[$group_id]) ) {
                    $group_friends[$group_id]['group_id'] = $group_id;
                    $group_friends[$group_id]['group_name'] = $group;
                    $group_friends[$group_id]['friends'] = [];
                }
                foreach ($friends as $friend) {
                    if ( $friend->group_id == $group_id ) {
                        $group_friends[$group_id]['friends'][] = ['friend_id' => $friend->friend_id, 'nick_name' => $friend->friend->nick_name, 'user_avatar' => $friend->friend->avatar->file_path, 'friend_remarks' => $friend->friend_remarks, 'created_time' => $friend->created_time,];
                    }
                }
            }
        }
        ksort($group_friends);

        // 重组数组
        $group_friends = array_values($group_friends);

        return $group_friends;
    }

    /**
     * 同意添加好友：相互成为好友
     *
     * @param  int  $user_id
     * @param  int  $friend_id
     *
     * @return bool
     */
    public static function agreeAddFriend(int $user_id, int $friend_id) : bool
    {
        $res1 = self::getInstance()->add(['user_id' => $user_id, 'friend_id' => $friend_id,]);
        $res2 = self::getInstance()->add(['user_id' => $friend_id, 'friend_id' => $user_id,]);
        return ($res1 && $res2) ? true : false;
    }

    // 批量设置好友到指定分组
    public function batchSetGroup(int $group_id, int $user_id, array $friend_ids) : bool
    {
        /**
         * 1.先删除所有存在该分组下面的好友关联关系
         * 2.按照当前设置的好友，进行关联当前分组
         */
        $this->startTrans();
        try {
            $this->where(['user_id' => $user_id, 'group_id' => $group_id,])->update(['group_id' => 0]);

            if ( $friend_ids ) $this->where('user_id', $user_id)
                                    ->where('friend_id', 'IN', $friend_ids)
                                    ->update(['group_id' => $group_id]);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }
}