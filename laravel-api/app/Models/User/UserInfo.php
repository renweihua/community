<?php

namespace App\Models\User;

use App\Models\Dynamic\Dynamic;
use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserInfoFactory;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\User\UserInfo
 *
 * @property int $user_id 用户的id-会员基本信息表
 * @property string $user_uuid UUID
 * @property string $pay_pass 支付密码
 * @property string $nick_name 昵称
 * @property string $user_avatar 头像
 * @property string $background_cover 背景封面图
 * @property int $user_sex 性别：0：男；1：女；2.保密
 * @property int $user_birth 出生年月日
 * @property string $city_info 城市信息：省份,城市
 * @property int $get_likes 获赞数
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property int $user_grade 用户等级
 * @property int $user_experience 用户经验
 * @property int $auth_status 实名认证状态：0：否，1：是
 * @property int $auth_mobile 手机号验证状态：0：否，1：是
 * @property int $auth_email 邮箱验证状态：0：否，1：是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $last_actived_time 上次活跃时间
 * @property int $notification_count 未读消息
 * @property int $sign_days 连续签到天数
 * @property int $last_sign_time 上次签到时间
 * @property int $total_sign_days 总共签到天数
 * @property int $year_sign_days 今年总共签到天数
 * @property int $register_type 注册方式：0：账户；1.邮箱；2.手机号；3.第三方登录
 * @property mixed|null $other_extends 会员的其它扩展信息
 * @property mixed|null $basic_extends 会员的基础扩展信息
 * @property mixed|null $auth_extends 会员的认证扩展信息
 * @property mixed|null $sign_extends 会员的签到扩展信息
 * @property int $luckydraw_times 抽奖的次数
 * @property-read \Illuminate\Database\Eloquent\Collection|Dynamic[] $dynamics
 * @property-read int|null $dynamics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\UserFollowFan[] $fans
 * @property-read int|null $fans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\UserFollowFan[] $follows
 * @property-read int|null $follows_count
 * @property mixed $a_u_t_h_extends
 * @property-read bool $is_sign
 * @property-read string $time_formatting
 * @property-read string $user_sex_text
 * @property-read \App\Models\User\UserFollowFan|null $isFollow
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereAuthEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereAuthExtends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereAuthMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereAuthStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereBackgroundCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereBasicExtends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereCityInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereGetLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereLastActivedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereLastSignTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereLuckydrawTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereNotificationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereOtherExtends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo wherePayPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereSignDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereSignExtends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereTotalSignDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereYearSignDays($value)
 * @mixin \Eloquent
 */
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
        if (
            substr($value, 0, 8) == 'https://'
            ||
            substr($value, 0, 7) == 'http://'
        ) return $value;
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
