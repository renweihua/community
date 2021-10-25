<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\FailException;
use App\Models\User\User;
use App\Models\User\UserEmailVerify;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Notifications\ActiveEmailSuccess;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
    public function lists(array $params = [], int $limit = 10, int $login_user_id = 0)
    {
        return User::filter($params)
            ->with(['userInfo' => function($query) use($login_user_id) {
                $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'get_likes', 'basic_extends'])
                    ->with(['isFollow'  => function($query) use ($login_user_id) {
                        $query->where('user_id', $login_user_id);
                    },]);
            }])
            ->paginate($this->getLimit($limit));
    }

    /**
     * 获取会员详情
     *
     * @param  $user_id [int:Id|string:uuid]
     * @param  int  $login_user_id
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function detail($user_id, int $login_user_id = 0)
    {
        if (is_numeric($user_id)){
            $user = User::with(['userInfo' => function($query) {
                $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'get_likes', 'basic_extends', 'other_extends']);
            }])->find($user_id);
        }else{
            $user = User::with(['userInfo' => function($query) {
                $query->select(['user_id', 'user_uuid', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'auth_status', 'auth_mobile', 'auth_email', 'created_time', 'last_actived_time', 'get_likes', 'basic_extends', 'other_extends']);
            }])->find(UserInfo::where('user_uuid', $user_id)->select(['user_id'])->first()->user_id);
        }
        if (empty($user)) {
            $this->setError('会员不存在！');
            return false;
        }
        $user->user_mobile = get_encryption_mobile($user->user_mobile);
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

    /**
     * 邮箱激活流程
     *
     * @param  string  $verify_token
     *
     * @return bool
     */
    public function verifyEmailToken(string $verify_token, bool $change = false): bool
    {
        $detail = UserEmailVerify::where('verify_token', $verify_token)->first();
        if (!$detail){
            return false;
        }
        // 废弃，激活与变更邮箱全部使用同一个流程
        //if ($detail->auth_email == 1){
        //    $this->setError('已认证！');
        //    return false;
        //}
        // 激活链接有效期为7天
        if ($detail->created_time + 7 * 24 * 3600 < time()){
            $this->setError('激活链接已过期！');
            return false;
        }
        if ($change){ // 变更邮箱的激活流程
            $user = User::where('user_id', $detail->user_id)->with(['userInfo' => function($query){
                $query->select('user_id', 'auth_email');
            }])->first();
        }else{
            $user = User::where('user_email', $detail->user_email)->with(['userInfo' => function($query){
                $query->select('user_id', 'auth_email');
            }])->first();
        }
        if (!$user){
            $this->setError('无效邮箱验证！');
            return false;
        }
        DB::beginTransaction();
        try{
            // 更新验证记录表
            $detail->update(['auth_email' => 1]);
            // 更改会员表的邮箱字段
            $user->update(['user_email' => $detail->user_email]);
            // 更新会员基本信息表的认证状态
            $user->userInfo->update(['auth_email' => 1]);

            // 发送已激活的消息
            Notification::route('mail', $detail->user_email)->notify(new ActiveEmailSuccess());

            DB::commit();
            $this->setError('激活成功！');
            return true;
        }catch (FailException $e){
            DB::rollBack();
            $this->setError('激活失败！');
            return false;
        }
    }
}
