<?php

namespace App\Models\User;

use App\Models\MonthModel;

/**
 * App\Models\User\UserSign
 *
 * @property int $sign_id 会员签到记录表
 * @property int $user_id 用户的id
 * @property int $sign_type 签到类型：0：会员签到；1：后台手动添加
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string $description 描述
 * @property string $created_ip 创建IP
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereSignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereSignType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSign whereUserId($value)
 * @mixin \Eloquent
 */
class UserSign extends MonthModel
{
    protected $primaryKey = 'sign_id';
    protected $is_delete = 0; //是否开启删除（1.开启删除，就是直接删除；0.假删除）

    /**
     * 获取会员今日签到记录
     *
     * @param  int  $user_id
     *
     * @return mixed
     */
    public function getTodayByUser(int $user_id)
    {
        return $this->where('user_id', $user_id)->whereBetween('created_time',
            [
                strtotime(date('Y-m-d', time()) . ' 00:00:00'),
                strtotime(date('Y-m-d', time()) . ' 23:59:59'),
            ])
            ->first();
    }
}
