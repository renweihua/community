<?php

namespace App\Model\User;

use App\Model\Model;

class UserEmailVerify extends Model
{
    protected $primaryKey = 'verify_id';

    public static function randRecord(User $user, string $user_email = '')
    {
        return self::create([
            'user_id' => $user->user_id,
            'user_email' => $user_email ?? $user->user_email,
            'verify_token' => make_uuid()
        ]);
    }
}
