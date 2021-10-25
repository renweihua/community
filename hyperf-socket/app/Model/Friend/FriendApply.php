<?php

namespace App\Model\Friend;

use App\Model\Model;

/**
 * App\Model\Friend\FriendApply
 *
 * @property int $apply_id 好友申请表
 * @property int $user_id 用户Id
 * @property int $friend_id 好友Id
 * @property mixed|null $message_record 消息记录 - 申请原因，申请方与接收方可以多次进行回复
 * @property int $is_check 是否审核：1：同意；0：待处理；2.拒绝
 * @property int $is_finish 是否已结束：0.否；1.是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereApplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereFriendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereIsFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereMessageRecord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendApply whereUserId($value)
 * @mixin \Eloquent
 */
class FriendApply extends Model
{

}
