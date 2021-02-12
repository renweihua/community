<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\User\UserSign;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class SignService extends Service
{
    /**
     * 签到流程
     *
     * @param  int  $login_user
     *
     * @return bool
     */
    public function signIn(int $login_user)
    {
        $userSignInstance = UserSign::getInstance();
        $today = $userSignInstance->where('user_id', $login_user)
                                  ->whereBetween('created_time',
                                      [
                                          strtotime(date('Y-m-d', time()) . ' 00:00:00'),
                                          strtotime(date('Y-m-d', time()) . ' 23:59:59'),
                                      ])
                                  ->first();
        if ($today) {
            $this->setError('今日您已签到！');
            return true;
        }
        if ($userSignInstance->create([
            'user_id' => $login_user,
            'created_ip' => get_ip(),
        ])) {
            $this->setError('签到成功！');
            return true;
        } else {
            $this->setError('签到失败！');
            return false;
        }
    }

}
