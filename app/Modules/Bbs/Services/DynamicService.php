<?php

namespace App\Modules\Bbs\Services;

use App\Models\Dynamic\Dynamic;
use App\Services\Service;

class DynamicService extends Service
{
    /**
     * 获取动态详情
     *
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function detail(int $dynamic_id)
    {
        $dynamic = Dynamic::check()->with([
            'userInfo' => function($query){
                $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade']);
            }
        ])->find($dynamic_id);
        if (empty($dynamic)) {
            $this->setError('动态不存在！');
            return false;
        }
        $this->setError('动态详情获取成功！');
        return $dynamic;
    }
}
