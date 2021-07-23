<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserOtherloginFactory;

class UserOtherlogin extends Model
{
    protected $primaryKey = 'user_id';

    protected static function newFactory()
    {
        return UserOtherloginFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, $this->primaryKey, $this->primaryKey);
    }
}
