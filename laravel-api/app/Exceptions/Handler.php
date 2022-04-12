<?php

namespace App\Exceptions;

use App\Traits\Json;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        if ($request->isJson() || $request->is('api/*')){
            // 路由404异常监听
            if($exception instanceof NotFoundHttpException){
                $this->setHttpCode(404);
                return $this->errorJson("路由{{$request->path()}}不存在！");
            }

            // 控制器不存在
            if ($exception instanceof BindingResolutionException){
                return $this->setJsonReturn($exception);
            }

            // 模型不存在
            if ($exception instanceof ModelNotFoundException){
                return $this->setJsonReturn($exception);
            }

            // 验证器类的错误监听
            if($exception instanceof ValidationException){
                return $this->errorJson($exception->validator->errors()->first());
            }

            // 路由的请求方式是否被支持
            if ($exception instanceof MethodNotAllowedHttpException){
                return $this->setJsonReturn($exception);
            }

            // 自定义Exception类的错误监听
            if($exception instanceof Exception){
                return $this->setJsonReturn($exception);
            }

            // ErrorException类的监听
            if($exception instanceof \ErrorException){
                return $this->setJsonReturn($exception);
            }

            // QueryException
            if ($exception instanceof QueryException){
                return $this->setJsonReturn($exception);
            }
            // Exception类的监听
            if($exception instanceof \Exception){
                return $this->setJsonReturn($exception);
            }
        }

        return parent::render($request, $exception);
    }

    private function setJsonReturn($exception)
    {
        $APP_DEBUG = env('APP_DEBUG');

        // 设置HTTP的状态码
        $http_status = isset($http_status) ? $http_status : (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 200);

        // 可设置`status`，但是也要限制
        $status = $exception->getCode() != 1 ? 0 : 1;

        return $this->errorJson($exception->getMessage(), $status, [], $APP_DEBUG ? [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'http_status' => (int)$http_status
        ] : []);
    }
}
