<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserFactory;

/**
 * App\Models\User\User
 *
 * @property int $user_id 会员登录表
 * @property string $user_mobile 手机号
 * @property string $user_name 用户名
 * @property string $user_email 邮箱
 * @property string $password 登录密码
 * @property string $login_token login_token【用于提示是否异地登录，jwt-token只能检测是否登录---这个效果其实用不用都可以的，需要看心情。】
 * @property int $is_check 是否审核：1：正常；0：禁用；2.踢出登录，重新登录
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @property-read \App\Models\User\UserInfo|null $userInfo
 * @property-read \App\Models\User\UserOtherlogin|null $userOtherlogin
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLoginToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserName($value)
 * @mixin \Eloquent
 */
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
        'login_token'
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

    /**
     * 邮箱的激活链接
     *
     * @param $token
     *
     * @return string
     */
    public function getActivationLink($token)
    {
        return route('user.activate', ['verify_token' => $token]);
    }

    /**
     * 更改邮箱的激活链接
     *
     * @param $token
     *
     * @return string
     */
    public function getChangeEmailLink($token)
    {
        return route('user.activate_change_email', ['verify_token' => $token]);
    }
}
