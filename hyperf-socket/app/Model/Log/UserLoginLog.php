<?php

namespace App\Model\Log;

use App\Model\MonthModel;
use App\Utils\ExecutionTime;

class UserLoginLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function record(int $user_id = 0, int $log_status = 1, $description = '登录成功', array $params = [])
    {
        $ip_agent = get_client_info();
        return $this->setMonthTable()->create([
            'user_id' => $user_id,
            'created_ip'   => $ip_agent['ip'] ?? get_ip(),
            'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            'log_status' => $log_status,
            'description' => $description,
            'log_duration' => microtime(true) - ExecutionTime::$start_time,
            'request_data' => json_encode($params),
        ]);
    }
}
