<?php

namespace App\Modules\Bbs\Services\User;

use App\Models\Log\UserLoginLog;
use App\Services\Service;

class LoginLogService extends Service
{
    /**
     * 指定月份的记录
     *
     * @param  int     $login_user_id
     * @param  string  $search_month
     *
     * @return array
     */
    public function getListsByMonth(int $login_user_id, string $search_month): array
    {
        $limit = $this->getLimit(request()->input('limit', 10));
        $paginates = UserLoginLog::getInstance()->setMonthTable($search_month)
            ->select('log_id', 'created_ip', 'description', 'log_status', 'created_time')
            ->where('user_id', $login_user_id)
            ->orderBy('log_id', 'DESC')
            ->paginate($limit);

        $lists = $this->getPaginateFormat($paginates);

        /**
         * 是否还存在更多数据：
         *  获取最后一条数据的时间戳为查询月份
         */
        if (empty($lists['data'])) {
            // 大于最小月份时，继续查询
            if ( date(UserLoginLog::MONTH_FORMAT, strtotime($search_month)) > UserLoginLog::MIN_TABLE ) {
                $search_month = date('Y-m', strtotime('-1 month', strtotime($search_month)));
                return $this->getListsByMonth($login_user_id, $search_month);
            }
            $lists['month_table'] = $search_month;
        } else {
            $lists['month_table'] = date('Y-m', current($lists['data'])['created_time']);
        }

        return $lists;
    }
}
