<?php

namespace App\Model\Dynamic;

use App\Model\Model;

/**
 * App\Model\Dynamic\DynamicCollection
 *
 * @property int $relation_id 动态收藏表
 * @property int $user_id 会员Id
 * @property int $dynamic_id 动态Id-收藏表
 * @property int $created_time 创建时间
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property-read \App\Model\Dynamic\Dynamic|null $dynamic
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereDynamicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicCollection whereUserId($value)
 * @mixin \Eloquent
 */
class DynamicCollection extends Model
{
    protected $primaryKey = 'relation_id';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // 新增与删除收藏时，调用动态的统计缓存字段
        $saveContent = function (self $dynamicPraise) {
            $dynamicPraise->dynamic->refreshCache();
        };

        static::created($saveContent);

        static::deleted($saveContent);
    }

    public function dynamic()
    {
        return $this->hasOne(Dynamic::class, 'dynamic_id', 'dynamic_id');
    }

    /**
     * 指定会员是否已【收藏】了指定动态
     *
     * @param  int  $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function isCollection(int $login_user, int $dynamic_id):bool
    {
        return $this->where([
            'user_id' => $login_user,
            'dynamic_id' => $dynamic_id,
        ])->first() ? true : false;
    }
}
