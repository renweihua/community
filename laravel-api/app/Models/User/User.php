<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserFactory;

class User extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password = 123456)
    {
        $this->attributes['password'] = hash_make($password);
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, $this->primaryKey, $this->primaryKey);
    }

    public function userOtherlogin()
    {
        return $this->hasOne(UserOtherlogin::class, $this->primaryKey, $this->primaryKey);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * 通过用户名搜索
     *
     * @param string $user_name
     *
     * @return mixed
     */
    public function getUserByName(string $user_name)
    {
        return $this->where('user_name', $user_name)->first();
    }

    /**
     * 通过邮箱进行搜索
     *
     * @param  string  $user_email
     *
     * @return mixed
     */
    public function getUserByEmail(string $user_email)
    {
        return $this->where('user_email', $user_email)->first();
    }

    /**
     * 通过手机号进行搜索
     *
     * @param  string  $user_email
     *
     * @return mixed
     */
    public function getUserByMobile(string $user_mobile)
    {
        return $this->where('user_mobile', $user_mobile)->first();
    }

    public function getActivationLink($token)
    {
        return route('user.activate', ['verify_token' => $token]);
    }
}
