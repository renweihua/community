<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserOtherloginFactory;

class UserOtherlogin extends Model
{
    protected static function newFactory()
    {
        return UserOtherloginFactory::new();
    }
}
