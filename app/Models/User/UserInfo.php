<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserInfoFactory;
use Illuminate\Support\Facades\Storage;

class UserInfo extends Model
{
    protected $primaryKey = 'user_id';
    // 追加属性
    protected $appends = ['user_sex_text'];

    public function fans()
    {
        return $this->hasMany(UserFollowFan::class, 'friend_id', $this->primaryKey);
    }

    public function follows()
    {
        return $this->hasMany(UserFollowFan::class, $this->primaryKey, $this->primaryKey);
    }

    public function isFollow()
    {
        return $this->hasOne(UserFollowFan::class, 'friend_id', $this->primaryKey);
    }

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
     * 返回性别文本
     *
     * @param $key
     *
     * @return string
     */
    public function getUserSexTextAttribute($key)
    {
        if (isset($this->attributes['user_sex'])){
            $text = '保密';
            switch ($this->attributes['user_sex']){
                case 0:
                    $text = '男';
                    break;
                case 1:
                    $text = '女';
                    break;
            }
            return $text;
        }
        return '';
    }

    /**
     * 指定会员的获赞数变动操作
     *
     * @param  int  $user_id
     * @param  int  $num
     *
     * @return mixed
     */
    public function setGetLikes(int $user_id, int $num = 1)
    {
        return $this->where('user_id', $user_id)->increment('get_likes', $num);
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
