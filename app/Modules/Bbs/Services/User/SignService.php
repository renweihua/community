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

    /**
     * 指定月份的签到状态
     *
     * @param  int     $login_user_id
     * @param  string  $month
     *
     * @return array
     */
    public function getSignsByMonth(int $login_user_id, string $month): array
    {
        // 获取指定月份的签到记录
        $data = UserSign::getInstance()->setMonthTable($month)->where('user_id', $login_user_id)->pluck('created_time');
        // 获取当月的所有日期天
        $days = get_month_days(strtotime($month));
        $lists = [];
        foreach ($days as $day){
            $lists[$day] = false;
            if (!empty($data)){
                foreach ($data as $created_time){
                    if (date('Y-m-d', $created_time) == $day){
                        $lists[$day] = true;
                        break;
                    }
                }
            }
        }
        return $lists;
    }

    public function index()
    {
        $where = [
            ['user_id', '=', $this->user_id],
        ];

        $primarykey = BbsUserSign::PRIMARYKEY;

        // 按照Id降序进行分页
        if ( !empty($this->request_data['last_id'])) {
            $where[] = [$primarykey, '<', $this->request_data['last_id']];
        }

        $list = BbsUserSign::operationWhere()
                           ->where($where)
                           ->limit($this->request_data['limit'])
                           ->orderBy($primarykey, 'DESC')
                           ->get();

        if ( !empty($list) && count($list)) {
            foreach ($list as &$v) {
                // 登录IP地址
                $v['created_ip'] = long2ip($v['created_ip']);
            }
            $last_id = min(array_column($list->toArray(), $primarykey));
        } else $last_id = 0;

        return self::apiWebReturn([
            'data' => $list,
            'last_id' => $last_id,
            'status' => 1,
        ]);
    }
    public function getSign()
    {
        // 今日是否签到
        $data['is_sign'] = 0;
        if ($sign = BbsUserSign::operationWhere()
                               ->where([
                                   ['created_time', '>=', strtotime(date('Y-m-d', time()) . ' 00:00:00')],
                                   ['created_time', '<=', strtotime(date('Y-m-d', time()) . ' 23:59:59')],
                               ])
                               ->first()) {
            $data = array_merge($data, $sign->toArray());
            $data['is_sign'] = 1;
        }
        // 连续签到次数
        $data['sign_days'] = rand(0, 100);
        // 漏签次数
        $data['miss_sign'] = rand(0, 100);
        // 今日签到第几名
        $data['sign_rank'] = rand(1, 1000);

        return self::apiWebReturn(['data' => $data]);
    }
}
