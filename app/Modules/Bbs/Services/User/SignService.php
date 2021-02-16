<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\User\UserInfo;
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
        DB::beginTransaction();
        $user_info = UserInfo::lock(true)->find($login_user);
        try{
            // 录入签到记录
            $userSignInstance->create([
                'user_id' => $login_user,
                'created_ip' => get_ip(),
            ]);

            // 计算登录会员的签到累计天数
            $user_info->setContinuousAttendance($user_info);

            DB::commit();
            $this->setError('签到成功！');
            return true;
        }catch (FailException $e){
            DB::rollBack();
            $this->setError('签到失败，请重试！');
            return false;
        }
    }

}
