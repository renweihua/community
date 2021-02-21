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
     * 今日签到信息
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getSignByToday(int $login_user_id)
    {
        $user_info = UserInfo::select('sign_days', 'last_sign_time', 'total_sign_days', 'year_sign_days')->find($login_user_id)->append(['is_sign']);
        // 今日签到了，那么计算我的签到排名
        if ($user_info->is_sign){
            $ranking = UserSign::getInstance()->whereBetween('created_time',
                [
                    strtotime(date('Y-m-d', time()) . ' 00:00:00'),
                    strtotime(date('Y-m-d', time()) . ' 23:59:59'),
                ])->where('created_time', '<', $user_info->last_sign_time)->count() + 1;
        }
        return [
            'sign_days' => $user_info->sign_days,
            'is_sign' => $user_info->is_sign, // 今日是否已签到
            'miss_sign' => get_days_in_year(date('Y')) - $user_info->year_sign_days, // 今年漏签的天数
            'sign_ranking' => $user_info->is_sign ? $ranking : 0, // 今日签到排名
            'seconds_to_tomorrow' => strtotime(date('Y-m-d', strtotime('+1 day'))) - time(), // 距离下一次签到还剩多少秒
        ];
    }

    /**
     * 签到流程
     *
     * @param  int  $login_user_id
     *
     * @return bool
     */
    public function signIn(int $login_user_id)
    {
        $userSignInstance = UserSign::getInstance();
        $today = $userSignInstance->getTodayByUser($login_user_id);
        if ($today) {
            $this->setError('今日您已签到！');
            return true;
        }
        DB::beginTransaction();
        $user_info = UserInfo::lock(true)->find($login_user_id);
        try{
            // 录入签到记录
            $userSignInstance->create([
                'user_id' => $login_user_id,
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

    /**
     * 指定月份的签到状态
     *
     * @param  int     $login_user_id
     * @param  string  $search_month
     *
     * @return array
     */
    public function getSignsStatusByMonth(int $login_user_id, string $search_month): array
    {
        // 获取指定月份的签到记录
        $data = UserSign::getInstance()->setMonthTable($search_month)->where('user_id', $login_user_id)->pluck('created_time');
        // 获取当月的所有日期天
        $days = get_month_days(strtotime($search_month));
        $lists = [];
        foreach ($days as $day){
            $lists[$day] = ['day' => $day, 'is_sign' => false];
            if (!empty($data)){
                foreach ($data as $created_time){
                    if ($lists[$day]['is_sign'] == false && date('Y-m-d', $created_time) == $day){
                        $lists[$day] = ['day' => $day, 'is_sign' => true];
                        break;
                    }
                }
            }
        }
        return array_values($lists);
    }

    /**
     * 指定月份的签到记录
     *
     * @param  int     $login_user_id
     * @param  string  $search_month
     *
     * @return array
     */
    public function getSignsByMonth(int $login_user_id, string $search_month): array
    {
        $limit = $this->getLimit(request()->input('limit', 10));
        $paginates = UserSign::getInstance()->setMonthTable($search_month)
            ->where('user_id', $login_user_id)
            ->orderBy('sign_id', 'DESC')
            ->paginate($limit);

        $lists = $this->getPaginateFormat($paginates);

        /**
         * 是否还存在更多数据：
         *  获取最后一条数据的时间戳为查询月份
         */
        if (empty($lists['data'])) {
            // 大于最小月份时，继续查询
            if ( date(UserSign::MONTH_FORMAT, strtotime($search_month)) > UserSign::MIN_TABLE ) {
                $search_month = date($search_month, strtotime('-1 month', strtotime($search_month)));
                return $this->getSignsByMonth($login_user_id, $search_month);
            }
            $lists['month_table'] = $search_month;
        } else {
            $lists['month_table'] = date('Y-m', current($lists['data'])['created_time']);
        }

        return $lists;
    }
}
