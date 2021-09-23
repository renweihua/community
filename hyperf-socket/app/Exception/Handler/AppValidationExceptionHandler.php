<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Exception\Exception;
use Hyperf\Di\Annotation\Inject;
use App\Constants\StatusConst;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpServer\Response;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppValidationExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        //阻止异常冒泡
        $this->stopPropagation();

        $msg = 'error';

        // Error 监听器
        if ($throwable instanceof \ErrorException){
            $msg = $throwable->getMessage();
        }

        // 自定义的异常类监听
        if ($throwable instanceof Exception){
            $msg = $throwable->getMessage();
        }

        // 验证器的错误类
        if ($throwable instanceof ValidationException){
            $msg = $throwable->validator->errors()->first();
        }

        return $this->response->json(['msg' => $msg, 'status' => StatusConst::ERROR, 'data' => []]);
    }

    /**
     *
     * @param Throwable $throwable 抛出的异常
     * @return bool 该异常处理器是否处理该异常
     */
    public function isValid(Throwable $throwable): bool
    {
        //当前的异常是否属于token验证异常
        return $throwable instanceof ValidationException;
    }
}
