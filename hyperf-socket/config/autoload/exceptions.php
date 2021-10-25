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
            App\Exception\Handler\AppExceptionHandler::class,

            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,

            // �쳣������
            App\Exception\Handler\AppValidationExceptionHandler::class,
        ],
    ],
];
