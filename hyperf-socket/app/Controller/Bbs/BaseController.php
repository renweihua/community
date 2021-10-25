<?php

declare(strict_types=1);

namespace App\Controller\Bbs;

use App\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected $service;

    public function getLoginUser()
    {
        return $this->request->getAttribute('login_user');
    }

    public function getLoginUserId(): int
    {
        return $this->getLoginUser()->user_id ?? 0;
    }
}
