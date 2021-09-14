<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,

            App\Exception\Handler\AppExceptionHandler::class,

            // 验证类的 异常处理器
            // \Hyperf\Validation\ValidationExceptionHandler::class,
            App\Exception\Handler\AppValidationExceptionHandler::class,
        ],
    ],
];
