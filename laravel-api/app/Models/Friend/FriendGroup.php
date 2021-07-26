<?php

namespace App\Models\Friend;

use App\Models\Model;

/**
 * App\Models\Friend\FriendGroup
 *
 * @property int $notify_id 好友分组表
 * @property int $user_id 会员Id
 * @property string $group_name 名称
 * @property int $friend_nums 好友数量
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereFriendNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereNotifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendGroup whereUserId($value)
 * @mixin \Eloquent
 */
class FriendGroup extends Model
{

}
