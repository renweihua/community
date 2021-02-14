<?php

namespace App\Modules\Bbs\Services\User;

use App\Models\User\UserInfo;
use App\Services\Service;
use Illuminate\Support\Facades\Storage;

class UserService extends Service
{
    /**
     * 个人资料编辑流程
     *
     * @param  int    $login_user_id
     * @param  array  $params
     *
     * @return bool
     */
    public function updateUser(int $login_user_id, array $params)
    {
        $userInfoInstance = UserInfo::getInstance();
        $user_info = $userInfoInstance->find($login_user_id);
        if (!$user_info){
            $this->setError('请重试！');
            return false;
        }
        if ($userInfoInstance->getUserInfoByNickName($params['nick_name'], $login_user_id)){
            $this->setError('该昵称已被占用！');
            return false;
        }

        // 更改编辑资料的字段设置
        $user_info->nick_name = $params['nick_name'];
        $user_info->user_sex = $params['user_sex'];
        $user_info->user_avatar = $params['user_avatar'];

        if ($user_info->save()){
            $this->setError('个人资料编辑成功！');
            return true;
        }else{
            $this->setError('更新失败！');
            return false;
        }
    }
}
