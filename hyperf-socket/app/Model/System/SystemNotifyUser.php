<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Model\Model;

class SystemNotifyUser extends Model
{
    public function notify()
    {
        return $this->hasOne(Notify::class, 'notify_id', 'notify_id');
    }
}