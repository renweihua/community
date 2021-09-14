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

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

return [
    'default' => [
        //'handler' => [
        //    'class' => RotatingFileHandler::class,
        //    'constructor' => [
        //        'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
        //        'level' => Logger::DEBUG,
        //    ],
        //],
        //'formatter' => [
        //    'class' => LineFormatter::class,
        //    'constructor' => [
        //        'format' => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
        //        'dateFormat' => 'Y-m-d H:i:s',
        //        'allowInlineLineBreaks' => true,
        //    ],
        //],

        'handlers' => [
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level'    => Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
                    'level'    => Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-notice.log',
                    'level'    => Logger::NOTICE,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-warning.log',
                    'level'    => Logger::WARNING,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-error.log',
                    'level'    => Logger::ERROR,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-critical.log',
                    'level'    => Logger::CRITICAL,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-alert.log',
                    'level'    => Logger::ALERT,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-emergency.log',
                    'level'    => Logger::EMERGENCY,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
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
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/crontab.log',
                    'level'    => Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
                    'constructor' => [
                        'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-notice.log',
                    'level'    => Logger::NOTICE,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
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
                'class'       => RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/sql.log',
                    'level'    => Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => LineFormatter::class,
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
