<?php

declare(strict_types = 1);

namespace App\Model\User;

use App\Model\Model;

class UserInfo extends Model
{
    protected $primaryKey = 'user_id';
    public    $is_delete  = 1;// 是否删除：0.假删除；1.真删除【默认全部假删除】

    public function user()
    {
        $key = $this->getKeyName();
        return $this->hasOne(User::class, $key, $key);
    }

    /**
     * 通过UUID进行搜索
     *
     * @param  string  $user_uuid
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getUserByUuid(string $user_uuid, array $with = [])
    {
        return $this->query()->where('user_uuid', $user_uuid)->with($with)->first();
    }

    public function detail(int $user_id)
    {
        return $this->find($user_id);
    }

    public function getUserAvatarAttribute($value)
    {
        if (empty($value)) return '';
        if (
            substr($value, 0, 8) == 'https://'
            ||
            substr($value, 0, 7) == 'http://'
        ) return $value;
        return get_file_url($value);
    }

    /**
     * 设置会员头像
     *
     * @param $value
     */
    public function setUserAvatarAttribute($value)
    {
        $this->attributes['user_avatar'] = set_file_url($value);
    }
}