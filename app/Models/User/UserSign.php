<?php

namespace App\Models\User;

use App\Models\MonthModel;

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
