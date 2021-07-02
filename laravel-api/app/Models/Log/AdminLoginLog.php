<?php

namespace App\Models\Log;

use App\Models\MonthModel;
use App\Models\Rabc\Admin;

class AdminLoginLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function getLogDurationAttribute($key)
    {
        return floatval($key);
    }

    public function add(int $admin_id = 0, int $log_status = 1, $description = '登录成功')
    {
        $ip_agent = get_client_info();
        return $this->setMonthTable()->create([
            'admin_id' => $admin_id,
            'created_ip'   => $ip_agent['ip'] ?? get_ip(),
            'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            'log_status' => $log_status,
            'description' => $description,
            'log_action'   => request()->route()->getActionName(),
            'log_method'   => request()->getMethod(),
            'log_duration' => microtime(true) - LARAVEL_START,
            'request_data' => json_encode(request()->all()),
        ]);
    }
}
