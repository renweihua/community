<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserGradeFactory;

/**
 * App\Models\User\UserGrade
 *
 * @property int $grade_id 会员等级表【禁止删除，允许修改】
 * @property string $grade_name 等级名称
 * @property string $min_value 最小经验值
 * @property string $max_value 最大经验值
 * @property int $grade_sort 排序
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereGradeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereGradeSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereMaxValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereMinValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGrade whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class UserGrade extends Model
{
    protected static function newFactory()
    {
        return UserGradeFactory::new();
    }
}
