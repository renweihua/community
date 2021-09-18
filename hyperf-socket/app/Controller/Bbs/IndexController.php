<?php

declare(strict_types=1);

namespace App\Controller\Bbs;

use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

class IndexController extends BaseController
{
    /**
     * @Inject()
     * @var IndexService
     */
    protected $service;

    /**
     * 首页：发现
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function discover() : ResponseInterface
    {
        $lists = $this->service->discover($this->getLoginUserId(), $this->request);
        return $this->successJson($lists);
    }
}
