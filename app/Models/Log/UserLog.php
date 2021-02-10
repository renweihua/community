<?php

namespace App\Models\Log;

use App\Models\MonthModel;

class UserLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;
}
