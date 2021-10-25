<?php

declare(strict_types = 1);

namespace App\Controller\Bbs;

use App\Service\Bbs\DynamicService;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

class IndexController extends BaseController
{
    /**
     * @Inject()
     * @var DynamicService
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

    /**
     * 首页：关注
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function follows() : ResponseInterface
    {
        $lists = $this->service->follows($this->getLoginUserId(), $this->request);
        return $this->successJson($lists);
    }
}
