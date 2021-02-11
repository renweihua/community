<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserInfoFactory;
use Illuminate\Support\Facades\Storage;

class UserInfo extends Model
{
    protected $primaryKey = 'user_id';

    protected static function newFactory()
    {
        return UserInfoFactory::new();
    }

    /**
     * 获取会员头像
     *
     * @param $key
     * @return string
     */
    public function getUserAvatarAttribute($key)
    {
        if (empty($key)) return '';
        return Storage::url($key);
    }

    /**
     * 设置会员头像
     *
     * @param $key
     */
    public function setUserAvatarAttribute($key)
    {
        if ( !empty($key) ) {
            $this->attributes['user_avatar'] = str_replace(Storage::url('/'), '', $key);
        }
    }

    /**
     * 生成唯一的uuid
     *
     * @return string
     */
    public static function getUniqueUuid()
    {
        $user_uuid = get_uuid();
        if (self::where('user_uuid', $user_uuid)->lock(true)->first()){
            return self::getUniqueUuid();
        }
        return $user_uuid;
    }
}
