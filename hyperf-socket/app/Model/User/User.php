<?php

declare(strict_types = 1);

namespace App\Model\User;

use App\Model\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';
    public    $timestamps = false;

    /**
     * 关联会员详情模型
     *
     * @return \Hyperf\Database\Model\Relations\HasOne
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, $this->primaryKey, $this->primaryKey);
    }

    public function userOtherlogin()
    {
        return $this->hasOne(UserOtherlogin::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'login_token'
    ];

    /**
     * 设置密码时，进行hash加密
     *
     * @param $value
     *
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        if ( !empty($value) ) $value = 123456;
        return $this->attributes['password'] = hash_encryption($value);
    }

    /**
     * 通过用户名搜索
     *
     * @param  string  $user_name
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getUserByName(string $user_name)
    {
        return $this->query()->where('user_name', $user_name)->first();
    }

    /**
     * 通过用户名搜索
     *
     * @param  string  $user_name
     * @param  array   $with
     *
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function getUsersBySearch(string $user_name, array $with = ['user_info.avatar'])
    {
        return $this->query()->where('user_name', 'like', "%{$user_name}%")->with($with)->get();
    }

    /**
     * 通过邮箱进行搜索
     *
     * @param  string  $user_email
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getUserByEmail(string $user_email)
    {
        return $this->query()->where('user_email', $user_email)->first();
    }

    /**
     * 通过手机号进行搜索
     *
     * @param  string  $user_mobile
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getUserByMobile(string $user_mobile)
    {
        return $this->query()->where('user_mobile', $user_mobile)->first();
    }

    public function detail(int $user_id, $with = [])
    {
        return $this->find($user_id, $with);
    }
}