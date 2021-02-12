<?php

namespace App\Modules\Bbs\Services;

use App\Models\User\User;
use App\Services\Service;

class UserService extends Service
{
    /**
     * 获取会员详情
     *
     * @param  int  $user_id
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function detail(int $user_id)
    {
        $user = User::with(['userInfo' => function($query) {
            $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'user_introduction', 'get_likes']);
        }])->find($user_id);
        if (empty($user)) {
            $this->setError('会员不存在！');
            return false;
        }
        $this->setError('会员详情获取成功！');
        return $user;
    }
}
