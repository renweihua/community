<?php

namespace App\Modules\Bbs\Services\User;

use App\Constants\UserCacheKeys;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Emails\ChangePasswordEmail;
use App\Services\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

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
    public function updateUser(int $login_user_id, array $params): bool
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

    /**
     * 更换背景封面图
     *
     * @param          $login_user
     * @param  string  $background_cover
     *
     * @return bool
     */
    public function updateBackgroundCover($login_user, string $background_cover): bool
    {
        $login_user->userInfo->background_cover = $background_cover;
        $login_user->userInfo->save();

        $this->setError('背景封面图设置成功！');
        return true;
    }

    /**
     * 更改登录密码
     *
     * @param          $login_user
     * @param  string  $password
     *
     * @return bool
     */
    public function changePassword($login_user, string $password): bool
    {
        $login_user->password = $password;
        $login_user->save();

        $this->setError('登录密码更改成功！');
        return true;
    }

    protected function getMailCode($login_user)
    {
        return Cache::get(UserCacheKeys::CHANGE_PASSWORD_EMAIL_CODE . $login_user->user_email);
    }

    /**
     * 更改登录密码时，通过邮箱：发送验证码
     *
     * @param $login_user
     *
     * @return bool
     */
    public function sendMailByChangePassword($login_user): bool
    {
        if ($this->getMailCode($login_user)){
            return true;
        }
        $code = random_verification_code(6);

        // 验证码存入缓存：默认1小时
        Cache::put(UserCacheKeys::CHANGE_PASSWORD_EMAIL_CODE . $login_user->user_email, $code, UserCacheKeys::KEY_DEFAULT_TIMEOUT);

        // 发送邮件
        Mail::to($login_user->user_email)->send(
            new ChangePasswordEmail($code)
        );

        return true;
    }

    /**
     * 通过邮箱更改登录密码
     *
     * @param          $login_user
     * @param  string  $code
     * @param  string  $password
     *
     * @return bool
     */
    public function checkEmailCodeAndUpdatePassword($login_user, string $code, string $password): bool
    {
        $cache = $this->getMailCode($login_user);
        if (!$cache){
            $this->setError('验证码已过期，请重新发送！');
            return false;
        }
        if ($cache != $code){
            $this->setError('验证码不匹配！');
            return false;
        }
        // 删除缓存
        Cache::forget(UserCacheKeys::CHANGE_PASSWORD_EMAIL_CODE . $login_user->user_email);

        // 更改登录密码
        $login_user->password = $password;
        $login_user->save();

        $this->setError('登录密码更改成功！');
        return true;
    }
}
