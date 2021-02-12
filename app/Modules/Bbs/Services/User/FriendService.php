<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\User\UserFollowFan;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class FriendService extends Service
{
    /**
     * 我的关注
     *
     * @param  int  $login_user
     *
     * @return array
     */
    public function getFollows(int $login_user)
    {
        $lists = UserFollowFan::where('user_id', $login_user)
                              ->with([
                                  'friendInfo' => function($query) {
                                      $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                                  },
                              ])
                              ->orderBy('relation_id', 'DESC')
                              ->paginate(10);

        foreach ($lists as $item) {
            // 默认都是已关注
            $item->friendInfo->is_follow = true;
        }

        return $this->getPaginateFormat($lists);
    }

    /**
     * 关注指定会员流程
     *
     * @param  int  $login_user
     * @param  int  $friend_id
     *
     * @return bool|bool[]
     */
    public function setFollow(int $login_user, int $friend_id)
    {
        $userFollowFan = UserFollowFan::getInstance();
        // 对方是否关注了登录会员
        $friend_follow = $userFollowFan->checkFollow($friend_id, $login_user);
        // 我是否关注对方
        $my_follow = $userFollowFan->checkFollow($login_user, $friend_id);

        $msg = $my_follow ? '取关' : '关注';
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $login_user,
                'friend_id' => $friend_id,
            ];
            if ($my_follow) {
                // 删除我关注对方
                $my_follow->delete();
                // 删除对方与我是互关的状态
                if ($friend_follow) $friend_follow->save(['cross_correlation' => 0]);
            } else {
                // 被关注，那么双方标记为互关，好友；更改对方的互关状态
                if ($friend_follow) {
                    $friend_follow->save(['cross_correlation' => 1]);

                    $data['cross_correlation'] = 1;
                }
                $userFollowFan->create(array_merge($data, [
                    'created_time' => time(),
                ]));
                // 互动消息：谁关注了您

            }

            DB::commit();
            $this->setError($msg . '成功！');
            return ['is_follow' => $my_follow ? false : true];
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($msg . '失败！');
            return false;
        }
    }
}
