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

    protected function getSearchMonth():string
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
    protected function getLimit(int $limit = 10):int
    {
        // 不可为0
        $limit = $limit <= 0 ? 10 : $limit;
        // 每页最多100条数据
        $limit = $limit > 100 ? 100 : $limit;
        return $limit;
    }

    /**
     * 分页数据结构，格式化处理
     *
     * @param $paginate
     *
     * @return array
     */
    public function getPaginateFormat($paginate):array
    {
        return [
            'current_page' => $paginate->currentPage(),
            'per_page' => $paginate->perPage(),
            'count_page' => $paginate->lastPage(),
            'total' => $paginate->total(),
            'data' => $paginate->items(),
        ];
    }
}
