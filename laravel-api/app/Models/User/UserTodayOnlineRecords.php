<?php

namespace App\Models\User;

use App\Models\Model;

/**
 * App\Models\User\UserTodayOnlineRecords
 *
 * @property int $record_id 每天在线会员的记录表
 * @property int $day_time 当天时间戳 - 年月日即可
 * @property mixed|null $user_json 会员Id记录JSON格式
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords whereDayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTodayOnlineRecords whereUserJson($value)
 * @mixin \Eloquent
 */
class UserTodayOnlineRecords extends Model
{

}
