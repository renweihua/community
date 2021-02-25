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
     * @param $value
     * @return string
     */
    public function getUserAvatarAttribute($value)
    {
        if (empty($value)) return '';
        return Storage::url($value);
    }

    /**
     * 设置会员头像
     *
     * @param $value
     */
    public function setUserAvatarAttribute($value)
    {
        $this->attributes['user_avatar'] = str_replace(Storage::url('/'), '', $value);
    }

    /**
     * 获取背景封面图
     *
     * @param $value
     *
     * @return string
     */
    public function getBackgroundCoverAttribute($value)
    {
        if (empty($value)) return '';
        return Storage::url($value);
    }

    /**
     * 设置背景封面图
     *
     * @param $value
     */
    public function setBackgroundCoverAttribute($value)
    {
        $this->attributes['background_cover'] = str_replace(Storage::url('/'), '', $value);
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
     * 通过上一次的签到时间，检测是否签到
     *
     * @param $key
     *
     * @return bool
     */
    public function getIsSignAttribute($key)
    {
        if (!empty($this->attributes['last_sign_time']) && date('Y-m-d') == date('Y-m-d', $this->attributes['last_sign_time'])) return true;
        else return false;
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

    /**
     * 通过昵称获取会员详情
     *
     * @param  string  $nick_name
     * @param  int     $user_id
     *
     * @return mixed
     */
    public function getUserInfoByNickName(string $nick_name, int $user_id = 0)
    {
        $model = $this;
        if (!empty($user_id)) {
            $model = $this->where('user_id', '<>',  $user_id);
        }
        return $model->where('nick_name', $nick_name)->first();
    }

    /**
     * 设置连续签天数
     *
     * @param $user_info
     *
     * @return mixed
     */
    public function setContinuousAttendance($user_info)
    {
        $time = time();
        $yesterday_time = strtotime('-1 day');
        if (
            $user_info->last_sign_time == 0
            ||
            !(
                $user_info->last_sign_time >= strtotime(date('Y-m-d', $yesterday_time) . ' 00:00:00')
                &&
                $user_info->last_sign_time <= strtotime(date('Y-m-d', $yesterday_time) . ' 23:59:59')
            )
        ){
            $user_info->sign_days = 1;
        }else{
            ++$user_info->sign_days;
        }
        $user_info->last_sign_time = $time;
        // 今年累计签到次数
        ++$user_info->year_sign_days;
        // 总共累计签到次数
        ++$user_info->total_sign_days;
        return $user_info->save();
    }

    public static function getListByIds(array $ids)
    {
        $list = self::whereIn('user_id', $ids)->select('user_id', 'nick_name', 'user_avatar', 'user_sex')->get()->toArray();
        return array_column($list, null, 'user_id');
    }
}
