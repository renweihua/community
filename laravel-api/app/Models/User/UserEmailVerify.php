<?php

namespace App\Models\User;

use App\Models\Model;

class UserEmailVerify extends Model
{
    protected $primaryKey = 'verify_id';

    public static function randRecord(User $user)
    {
        return self::create([
            'user_id' => $user->user_id,
            'user_email' => $user->user_email,
            'verify_token' => make_uuid()
        ]);
    }
}
