<?php

namespace App\Modules\Bbs\Services;

use App\Models\User\User;
use App\Services\Service;

class UserService extends Service
{
    /**
     * 会员列表
     *
     * @param  array  $params
     * @param  int    $limit
     *
     * @return mixed
     */
    public function lists(array $params = [], int $limit = 10)
    {
        return User::filter($params)
            ->with(['userInfo' => function($query) {
                $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'user_introduction', 'get_likes']);
            }])
            ->paginate($this->getLimit($limit));
    }

    /**
     * 获取会员详情
     *
     * @param  int  $user_id
     * @param  int  $login_user_id
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function detail(int $user_id, int $login_user_id = 0)
    {
        $user = User::with(['userInfo' => function($query) {
            $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'user_introduction', 'get_likes']);
        }])->find($user_id);
        if (empty($user)) {
            $this->setError('会员不存在！');
            return false;
        }
        // 是否已关注
        $user->userInfo->is_follow = false;
        if ( !empty($login_user_id)) {
            $user->load([
                'userInfo.isFollow' => function($query) use ($login_user_id) {
                    $query->where('user_id', $login_user_id);
                },
            ]);
            $user->userInfo->is_follow = $user->userInfo->isFollow ? true : false;
            unset($user->userInfo->isFollow);
        }
        $this->setError('会员详情获取成功！');
        return $user;
    }
}
