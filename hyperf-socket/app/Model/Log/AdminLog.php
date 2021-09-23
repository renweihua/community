<?php

namespace App\Model\Log;

use App\Model\MonthModel;
use App\Model\Rabc\Admin;
use Illuminate\Support\Facades\URL;

class AdminLog extends MonthModel
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

    public static function addRecord(int $admin_id = 0, string $description = '')
    {
        $method = strtoupper(request()->getMethod());
        $ip_agent = get_client_info();
        return self::create([
            'request_data' => json_encode(request()->all()),
            'admin_id'     => $admin_id,
            'description'     => $description,
            'created_ip'   => $ip_agent['ip'] ?? get_ip(),
            'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            'created_time' => time(),
            'log_action'   => request()->route()->getActionName(),
            'log_method'   => $method,
            'log_duration' => microtime(true) - LARAVEL_START,
            'request_url'     => URL::full() ?? get_this_url(),
        ]);
    }
}
