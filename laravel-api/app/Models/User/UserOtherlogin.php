<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserOtherloginFactory;

/**
 * App\Models\User\UserOtherlogin
 *
 * @property int $user_id 用户的id-会员基本信息表
 * @property string $qq_openid QQ登录的标识
 * @property string $baidu_openid 百度登录的标识
 * @property string $weibo_openid 微博登录的标识
 * @property string $github_openid github的标识
 * @property string $weixin_openid 微信的标识
 * @property int $user_origin 来源：
 *                 0：普通注册；
 *                 1：QQ快捷登录；
 *                 2：百度登录；
 *                 3：微博登录；
 *                 4：Github登录；
 *                 5：小丑疯狂吧账户同步登录【已下线，暂不考虑】
 *                 6：微信登录【不给个人，暂不考虑】
 * @property int $change_account 是否允许更改账户：0.否；1.是【仅针对于第三方快捷登录的账户，仅可更改一次，值变动】
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property-read \App\Models\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereBaiduOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereChangeAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereGithubOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereQqOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereUserOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereWeiboOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOtherlogin whereWeixinOpenid($value)
 * @mixin \Eloquent
 */
class UserOtherlogin extends Model
{
    protected $primaryKey = 'user_id';

    protected static function newFactory()
    {
        return UserOtherloginFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, $this->primaryKey, $this->primaryKey);
    }
}
