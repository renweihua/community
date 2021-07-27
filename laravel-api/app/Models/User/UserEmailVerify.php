<?php

namespace App\Models\User;

use App\Models\Model;

/**
 * App\Models\User\UserEmailVerify
 *
 * @property int $verify_id 邮箱验证表
 * @property int $user_id 会员Id
 * @property string $user_email 邮箱
 * @property string $verify_token 验证TOKEN
 * @property int $auth_email 邮箱验证状态：0：否，1：是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereAuthEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereVerifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailVerify whereVerifyToken($value)
 * @mixin \Eloquent
 */
class UserEmailVerify extends Model
{
    protected $primaryKey = 'verify_id';

    public static function randRecord(User $user, string $user_email = '')
    {
        return self::create([
            'user_id' => $user->user_id,
            'user_email' => $user_email ?? $user->user_email,
            'verify_token' => make_uuid()
        ]);
    }
}
