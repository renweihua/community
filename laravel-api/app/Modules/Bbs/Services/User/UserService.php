<?php

namespace App\Modules\Bbs\Services\User;

use App\Constants\UserCacheKeys;
use App\Exceptions\Exception;
use App\Models\User\User;
use App\Models\User\UserEmailVerify;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Emails\ChangePasswordEmail;
use App\Modules\Bbs\Jobs\SendChangeEmail;
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
            throw new Exception('请重试！');
        }
        if ($userInfoInstance->getUserInfoByNickName($params['nick_name'], $login_user_id)){
            throw new Exception('该昵称已被占用！');
        }

        // 更改编辑资料的字段设置
        $user_info->nick_name = $params['nick_name'];
        $user_info->user_sex = $params['user_sex'];
        $user_info->user_avatar = $params['user_avatar'];
        if (isset($params['other_extends'])) $user_info->other_extends = $params['other_extends'];
        $user_info->basic_extends = array_merge($user_info->basic_extends, [
            'location' => $params['basic_extends']['location'] ?? $user_info->basic_extends['location'],
            'user_birth' => $params['basic_extends']['user_birth'] ?? $user_info->basic_extends['user_birth'],
            'user_introduction' => $params['basic_extends']['user_introduction'] ?? $user_info->basic_extends['user_introduction'],
        ]);

        if ($user_info->save()){
            $this->setError('个人资料编辑成功！');
            return true;
        }else{
            throw new Exception('更新失败！');
        }
    }

    /**
     * 更换头像
     *
     * @param          $login_user
     * @param  string  $user_avatar
     *
     * @return bool
     */
    public function updateAvatarCover($login_user, string $user_avatar): bool
    {
        $login_user->userInfo->user_avatar = $user_avatar;
        $login_user->userInfo->save();

        $this->setError('头像设置成功！');
        return true;
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
     * 编辑扩展信息
     *
     * @param         $login_user
     * @param  array  $params
     *
     * @return bool
     */
    public function updateExtend($login_user, array $params): bool
    {
        if (isset($params['other_extends'])){
            $login_user->userInfo->other_extends = array_merge($login_user->userInfo->other_extends, [
                'github' => $params['other_extends']['github'] ?? $login_user->userInfo->other_extends['github'],
                'twitter' => $params['other_extends']['twitter'] ?? $login_user->userInfo->other_extends['twitter'],
                'facebook' => $params['other_extends']['facebook'] ?? $login_user->userInfo->other_extends['facebook'],
                'instagram' => $params['other_extends']['instagram'] ?? $login_user->userInfo->other_extends['instagram'],
                'telegram' => $params['other_extends']['telegram'] ?? $login_user->userInfo->other_extends['telegram'],
                'coding' => $params['other_extends']['coding'] ?? $login_user->userInfo->other_extends['coding'],
                'steam' => $params['other_extends']['steam'] ?? $login_user->userInfo->other_extends['steam'],
                'weibo' => $params['other_extends']['weibo'] ?? $login_user->userInfo->other_extends['weibo'],
            ]);
        }
        $login_user->userInfo->save();
        $this->setError('设置成功！');
        return true;
    }

    /**
     * 更改登录账户
     *
     * @param          $login_user
     * @param  string  $password
     *
     * @return bool
     */
    public function changeUserName($login_user, string $user_name): bool
    {
        if ($login_user->userOtherlogin->change_account != 1){
            throw new Exception('暂时不允许更改登录账户！');
        }

        $login_user->user_name = $user_name;
        $login_user->save();
        
        $login_user->userOtherlogin()->update(['change_account' => 0]);

        $this->setError('登录账户更改成功！');
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
            throw new Exception('验证码已过期，请重新发送！');
        }
        if ($cache != $code){
            throw new Exception('验证码不匹配！');
        }
        // 删除缓存
        Cache::forget(UserCacheKeys::CHANGE_PASSWORD_EMAIL_CODE . $login_user->user_email);

        // 更改登录密码
        $login_user->password = $password;
        $login_user->save();

        $this->setError('登录密码更改成功！');
        return true;
    }

    /**
     * 变更邮箱
     *
     * @param          $login_user
     * @param  string  $user_email
     *
     * @return bool
     */
    public function changeEmail($login_user, string $user_email): bool
    {
        // 生成待激活邮箱的记录
        $email_verify = UserEmailVerify::randRecord($login_user, $user_email);

        // 注册成功：邮箱激活
        SendChangeEmail::dispatch($login_user, $user_email, $email_verify->verify_token)
            ->onConnection('database') // job 存储的服务：当前存储mysql
            ->onQueue('mail-queue'); // mail-queue 队列

        $this->setError('确认邮件已发送到新邮箱，请注意查收！');
        return true;
    }
}
