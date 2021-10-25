<?php

namespace App\Model\Log;

use App\Model\MonthModel;

class UserLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;
}
