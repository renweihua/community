<?php

namespace App\Models\User;

use App\Models\Dynamic\Dynamic;
use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserInfoFactory;
use Illuminate\Support\Facades\Storage;

class UserInfo extends Model
{
    protected $primaryKey = 'user_id';
    // 追加属性
    protected $appends = ['user_sex_text', 'time_formatting'];

    // 刷新统计数据
    public function refreshCache()
    {
        return $this->update(['basic_extends' => \array_merge(self::BASIC_EXTENDS_FIELDS, [
            'user_birth' => $this->basic_extends['user_birth'],
            'location' => $this->basic_extends['location'],
            'user_introduction' => $this->basic_extends['user_introduction'],
            'get_likes' => $this->basic_extends['get_likes'],
            'dynamics_count' => $this->dynamics()->count(),
            'fans_count' => $this->fans()->count(),
            'follows_count' => $this->follows()->count(),
        ])]);
    }

    // 会员的基础扩展字段
    const BASIC_EXTENDS_FIELDS = [
        'user_birth' => '', // 生日
        'location' => '', // 所在城市地区
        'user_introduction' => '', // 个人介绍
        'get_likes' => 0, //获赞数
        'dynamics_count' => 0, // 动态总量
        'fans_count' => 0, // 粉丝数量
        'follows_count' => 0, // 关注数量
    ];

    public function getBasicExtendsAttribute()
    {
        return \array_merge(self::BASIC_EXTENDS_FIELDS, json_decode($this->attributes['basic_extends'] ?? '{}', true));
    }

    public function setBasicExtendsAttribute($value)
    {
        $this->attributes['basic_extends'] = json_encode(array_merge(json_decode($this->attributes['basic_extends'] ?? '{}', true), $value));
    }

    // 会员的认证扩展字段
    const AUTH_EXTENDS_FIELDS = [
        'auth_status' => 0, // 实名认证状态：0：否，1：是
        'auth_mobile' => 0, // 手机号验证状态：0：否，1：是
        'auth_email' => 0, // 邮箱验证状态：0：否，1：是
    ];

    public function getAUTHExtendsAttribute()
    {
        return \array_merge(self::AUTH_EXTENDS_FIELDS, json_decode($this->attributes['auth_extends'] ?? '{}', true));
    }

    public function setAUTHExtendsAttribute($value)
    {
        $this->attributes['auth_extends'] = json_encode(array_merge(json_decode($this->attributes['auth_extends'] ?? '{}', true), $value));
    }

    // 会员的签到扩展字段
    const SIGN_EXTENDS_FIELDS = [
        'total_sign_days' => 0, // 总共签到天数
        'year_sign_days' => 0, // 今年总共签到天数
        'sign_days' => 0, // 连续签到天数
        'last_sign_time' => 0, // 上一次签到时间
    ];

    public function getSignExtendsAttribute()
    {
        return \array_merge(self::SIGN_EXTENDS_FIELDS, json_decode($this->attributes['sign_extends'] ?? '{}', true));
    }

    public function setSignExtendsAttribute($value)
    {
        $this->attributes['sign_extends'] = json_encode(array_merge(json_decode($this->attributes['sign_extends'] ?? '{}', true), $value));
    }

    // 会员的扩展字段
    const OTHER_EXTENDS_FIELDS = [
        'company' => '',
        'home_url' => '',
        'github' => '',
        'twitter' => '',
        'facebook' => '',
        'instagram' => '',
        'telegram' => '',
        'coding' => '',
        'steam' => '',
        'weibo' => '',
    ];

    public function getOtherExtendsAttribute()
    {
        return \array_merge(self::OTHER_EXTENDS_FIELDS, json_decode($this->attributes['other_extends'] ?? '{}', true));
    }

    public function setOtherExtendsAttribute($value)
    {
        $this->attributes['other_extends'] = json_encode(array_merge(json_decode($this->attributes['other_extends'] ?? '{}', true), $value));
    }

    public function dynamics()
    {
        return $this->hasMany(Dynamic::class, $this->primaryKey, $this->primaryKey);
    }

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
        if (check_url($value)) return $value;
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
                case 1:
                    $text = '男';
                    break;
                case 2:
                    $text = '女';
                    break;
            }
            return $text;
        }
        return '';
    }

    // 时间戳格式化
    public function getTimeFormattingAttribute($value): string
    {
        if (!isset($this->attributes['created_time'])) return '';
        return formatting_timestamp($this->attributes['created_time']);
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
