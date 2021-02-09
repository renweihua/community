<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserInfoFactory;

class UserInfo extends Model
{
    protected static function newFactory()
    {
        return UserInfoFactory::new();
    }
}
