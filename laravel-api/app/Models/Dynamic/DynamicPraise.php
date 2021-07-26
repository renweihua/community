<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\User;
use App\Models\User\UserInfo;

/**
 * App\Models\Dynamic\DynamicPraise
 *
 * @property int $relation_id 动态点赞表
 * @property int $user_id 会员Id
 * @property int $dynamic_id 动态Id-点赞表
 * @property int $created_time 创建时间
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property-read \App\Models\Dynamic\Dynamic $dynamic
 * @property-read mixed $updated_time
 * @property-read User $user
 * @property-read UserInfo $userInfo
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereDynamicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicPraise whereUserId($value)
 * @mixin \Eloquent
 */
class DynamicPraise extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // 新增与删除点赞时，调用动态的统计缓存字段
        $saveContent = function (self $dynamicPraise) {
            $dynamicPraise->dynamic->refreshCache();
        };

        static::created($saveContent);

        static::deleted($saveContent);
    }

    public function dynamic()
    {
        return $this->belongsTo(Dynamic::class, 'dynamic_id', 'dynamic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    /**
     * 指定会员是否已【点赞】了指定动态
     *
     * @param  int  $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function isPraise(int $login_user, int $dynamic_id): bool
    {
        return $this->where([
            'user_id' => $login_user,
            'dynamic_id' => $dynamic_id,
        ])->first() ? true : false;
    }
}
