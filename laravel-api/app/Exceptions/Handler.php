<?php

namespace App\Exceptions;

use App\Traits\Json;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use Json;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // 路由404异常监听
        if($exception instanceof NotFoundHttpException){
            $this->setHttpCode(404);
            return $this->errorJson("路由{{$request->path()}}不存在！");
        }

        // 控制器不存在
        if ($exception instanceof BindingResolutionException){
            return $this->errorJson($exception->getMessage());
        }

        // 模型不存在
        if ($exception instanceof ModelNotFoundException){
            return $this->errorJson($exception->getMessage());
        }

        // 验证器类的错误监听
        if($exception instanceof ValidationException){
            return $this->errorJson($exception->validator->errors()->first());
        }

        // Exception类的错误监听
        if($exception instanceof Exception){
            return $this->errorJson($exception->getMessage(), $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
