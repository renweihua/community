<?php

namespace App\Controller\Bbs;

use App\Request\Bbs\RegisterRequest;
use App\Request\Bbs\LoginRequest;
use App\Request\Bbs\EmailRequest;
use App\Request\TestRequest;
use PHPUnit\Util\Test;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use App\Service\Bbs\AuthService;

class AuthController extends BaseController
{
    /**
     * @Inject()
     * @var AuthService
     */
    protected $service;

    /**
     * 邮箱注册，发送验证码
     *
     * @param  \App\Request\Bbs\EmailRequest  $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getCodeByEmail(EmailRequest $request): ResponseInterface
    {
        $data = $request->all();

        $result = $this->service->sendCodeByEmail($data['user_email']);
        return $this->successJson([], '邮件已发送，请及时查看！');
    }

    /**
     * 注册
     *
     * @param  \App\Request\Bbs\RegisterRequest  $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(RegisterRequest $request): ResponseInterface
    {
        $data = $request->all();

        // 注册流程
        if ($user = $this->service->register($data)){
            return $this->successJson($user, $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 登录
     *
     * @param  \App\Request\Bbs\LoginRequest  $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(LoginRequest $request): ResponseInterface
    {
        $data = $request->validated();

        // 登录流程
        $user = $this->service->login($data, false);

        return $this->successJson($user, '登录成功！');
    }

    /**
     * 获取登录会员信息
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function me(): ResponseInterface
    {
        return $this->successJson($this->service->me($this->request), '会员信息获取成功！');
    }

    /**
     * 退出登录
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        $this->service->logout($this->request->header('Authorization'));
        return $this->successJson([], '退出成功！');
    }
}
