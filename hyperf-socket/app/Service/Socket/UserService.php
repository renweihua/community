<?php

namespace App\Service\Socket;

use App\Service\Service;

class UserService extends Service
{
    // 在线sid对应的会员信息
    const USERS_LIST = 'online-users';
    // 会员Id对应sid
    const USER_SID = 'userId-sid';

    public static function getUsers()
    {
        return redis('token')->hGetAll(self::USERS_LIST);
    }

    public static function getUser($socket)
    {
        return redis('token')->hGet(self::USERS_LIST, (string)$socket->getSid());
    }

    public static function getUserIdBySid(string $sid) : int
    {
        return intval(my_json_decode(redis('token')->hGet(self::USERS_LIST, $sid))['user_id']);
    }

    public static function getSidByUserId($user_id) : string
    {
        return redis('token')->hGet(self::USER_SID, (string)$user_id);
    }

    public static function setUser($socket, object $user) : bool
    {
        $sid = (string)$socket->getSid();
        $redis = redis('token');
        $redis->hDel(self::USERS_LIST, $sid);
        $redis->hDel(self::USER_SID, $user->user_id);

        // 记录加入房间的用户标识：用户Id与socket_id进行绑定
        $res1 = $redis->hSet(self::USERS_LIST, $sid, my_json_encode([
            'user_id'     => $user->user_id,
            // 会员Id
            'nick_name'   => $user->nick_name,
            // 昵称
            'user_avatar' => $user->user_avatar,
            // 头像：可访问的URL地址
        ]));
        // 会员Id与sid列表，方便通过sid获取到会员Id
        $res2 = $redis->hSet(self::USER_SID, (string)$user->user_id, $sid);
        return $res1 && $res2 ? true : false;
    }

    public static function deleteUser($socket)
    {
        $redis = redis('token');
        $redis->hDel(self::USERS_LIST, (string)$socket->getSid());
        $redis->hDel(self::USER_SID, self::getUserIdBySid((string)$socket->getSid()));
        return true;
    }
}