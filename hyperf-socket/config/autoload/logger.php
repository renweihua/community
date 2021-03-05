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

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

return [
    'default' => [
        //'handler' => [
        //    'class' => Monolog\Handler\RotatingFileHandler::class,
        //    'constructor' => [
        //        'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
        //        'level' => Monolog\Logger::DEBUG,
        //    ],
        //],
        //'formatter' => [
        //    'class' => Monolog\Formatter\LineFormatter::class,
        //    'constructor' => [
        //        'format' => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
        //        'dateFormat' => 'Y-m-d H:i:s',
        //        'allowInlineLineBreaks' => true,
        //    ],
        //],

        'handlers' => [
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level'    => Monolog\Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
                    'level'    => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-notice.log',
                    'level'    => Monolog\Logger::NOTICE,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-warning.log',
                    'level'    => Monolog\Logger::WARNING,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-error.log',
                    'level'    => Monolog\Logger::ERROR,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-critical.log',
                    'level'    => Monolog\Logger::CRITICAL,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-alert.log',
                    'level'    => Monolog\Logger::ALERT,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-emergency.log',
                    'level'    => Monolog\Logger::EMERGENCY,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
    'crontab' => [
        'handlers' => [
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/crontab.log',
                    'level'    => Monolog\Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-notice.log',
                    'level'    => Monolog\Logger::NOTICE,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
    'sql' => [
        'handlers' => [
            [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/sql.log',
                    'level'    => Monolog\Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
];
