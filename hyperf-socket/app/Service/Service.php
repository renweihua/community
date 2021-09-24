<?php

declare(strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use App\Traits\Error;
use App\Traits\Instance;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;

abstract class Service
{
    use Instance;
    use Error;

    // /**
    //  * @var ContainerInterface
    //  */
    // protected $container;
    // /**
    //  * @var StdoutLoggerInterface
    //  */
    // protected $logger;
    //
    // public function __construct(ContainerInterface $container)
    // {
    //     $this->container = $container;
    //     $this->logger = $container->get(StdoutLoggerInterface::class);
    // }

    protected function getSearchMonth():string
    {
        $search_month = $this->request->input('search_month', '');
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
    public function getPaginateFormat($paginate): array
    {
        $paginate = $paginate->toArray();
        $paginate['count_page'] = $paginate['last_page'];
        return $paginate;
        return [
            'current_page' => $paginate->currentPage(),
            'per_page' => $paginate->perPage(),
            'count_page' => $paginate->lastPage(),
            'total' => $paginate->total(),
            'data' => $paginate->items(),
            'path' => $paginate->path(),
            'first_page_url' => $paginate->get(),
        ];
    }
}
