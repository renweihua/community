<?php

namespace App\Services;

use App\Traits\Error;
use App\Traits\Instance;
use App\Traits\Json;

abstract class Service
{
    use Error;
    use Instance;
    use Json;

    protected function getSearchMonth()
    {
        $search_month = request()->input('search_month', '');
        if (empty($search_month)) return '';
        return $search_month;
    }

    /**
     * 列表页：部分接口需要自定义展示数量，对于数量做一个限制处理
     *
     * @param  int  $limit
     *
     * @return int
     */
    protected function getLimit(int $limit = 10)
    {
        // 不可为0
        $limit = $limit <= 0 ? 10 : $limit;
        // 每页最多100条数据
        $limit = $limit > 100 ? 100 : $limit;
        return $limit;
    }
}
