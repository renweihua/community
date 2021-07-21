<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\System\Notify;
use App\Models\User\UserFollowFan;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class FriendService extends Service
{
    /**
     * 我的关注
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getFollows(int $login_user_id)
    {
        $lists = UserFollowFan::where('user_id', $login_user_id)
                              ->with([
                                  'friendInfo' => function($query) {
                                      $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'basic_extends', 'user_uuid');
                                  },
                              ])
                              ->orderBy('relation_id', 'DESC')
                              ->paginate(10);
        foreach ($lists as $key => $item){
            if ($item->friendInfo) $item->friendInfo->is_friend = true;
            else unset($lists[$key]);
        }
        return $this->getPaginateFormat($lists);
    }

    /**
     * 我的粉丝
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getFans(int $login_user_id)
    {
        $lists = UserFollowFan::where('friend_id', $login_user_id)
                              ->with([
                                  'userInfo' => function($query) {
                                      $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'basic_extends', 'user_uuid');
                                  },
                              ])
                              ->orderBy('relation_id', 'DESC')
                              ->paginate(10);

        return $this->getPaginateFormat($lists);
    }

    /**
     * 关注指定会员流程
     *
     * @param  int  $login_user_id
     * @param  int  $friend_id
     *
     * @return bool|bool[]
     */
    public function setFollow(int $login_user_id, int $friend_id)
    {
        $userFollowFan = UserFollowFan::getInstance();
        // 对方是否关注了登录会员
        $friend_follow = $userFollowFan->checkFollow($friend_id, $login_user_id);
        // 我是否关注对方
        $my_follow = $userFollowFan->checkFollow($login_user_id, $friend_id);

        $msg = $my_follow ? '取关' : '关注';
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $login_user_id,
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
                $detail = $userFollowFan->create(array_merge($data, [
                    'created_time' => time(),
                ]));

                // 系统消息：谁关注了您
                if ($friend_id != $login_user_id){
                    if (!Notify::insert([
                        'notify_type'  => Notify::NOTIFY_TYPE['SYSTEM_MSG'],
                        'user_id'      => $friend_id,
                        'target_id'    => $detail->relation_id,
                        'target_type'  => Notify::TARGET_TYPE['FOLLOW'],
                        'sender_id'    => $login_user_id,
                        'sender_type'  => Notify::SYSTEM_SENDER,
                    ]) ) {
                        throw new FailException('互动消息录入失败！');
                    }
                }
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

    /**
     * 指定会员，设置是否特别关注
     *
     * @param  int  $login_user_id
     * @param  int  $friend_id
     *
     * @return bool
     */
    public function setSpecial(int $login_user_id, int $friend_id, int $is_cancel = 0): bool
    {
        $userFollowFan = UserFollowFan::getInstance();
        // 我是否关注对方
        $my_follow = $userFollowFan->checkFollow($login_user_id, $friend_id);
        if (!$my_follow){
            $this->setError('您尚未关注对方，无法设置特别关注！');
            return false;
        }

        // 保证设置成功，手动更新updated_time
        $my_follow->save(['is_special' => $is_cancel == 1 ? 0 : 1, 'updated_time' => time()]);
        $this->setError(($is_cancel == 1 ? '取消' : '') . '‘特别关注’设置成功！');
        return true;
    }

    /**
     * 指定会员，设置是否黑名单
     *
     * @param  int  $login_user_id
     * @param  int  $friend_id
     * @param  int  $is_cancel
     *
     * @return bool
     */
    public function setBlacklist(int $login_user_id, int $friend_id, int $is_cancel = 0): bool
    {
        $userFollowFan = UserFollowFan::getInstance();
        // 我是否关注对方
        $my_follow = $userFollowFan->checkFollow($login_user_id, $friend_id);
        if (!$my_follow){
            $this->setError('您尚未关注对方，无法设为黑名单属性！');
            return false;
        }

        // 保证设置成功，手动更新updated_time
        $my_follow->save(['is_blacklist' => $is_cancel == 1 ? 0 : 1, 'updated_time' => time()]);
        $this->setError(($is_cancel == 1 ? '取消' : '') . '‘拉黑’设置成功！');
        return true;
    }
}
