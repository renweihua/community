<?php

namespace App\Model\Log;

use App\Model\MonthModel;

class WebLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function getLogDurationAttribute($key)
    {
        return floatval($key);
    }
}
