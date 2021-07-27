<?php

namespace App\Models\Friend;

use App\Models\Model;

/**
 * App\Models\Friend\Friend
 *
 * @property int $relation_id 好友表
 * @property int $user_id 用户Id
 * @property int $friend_id 好友Id
 * @property string $friend_remark 备注
 * @property int $is_special 是否特别关心：1：是；0：否
 * @property int $is_blacklist 是否拉黑：1：是；0：否
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereFriendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereFriendRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereIsBlacklist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereUserId($value)
 * @mixin \Eloquent
 */
class Friend extends Model
{

}
