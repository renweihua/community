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
use Hyperf\HttpServer\Router\Router;

//Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

//Router::get('/favicon.ico', function () {
//    return '';
//});

foreach (get_dir_files(BASE_PATH . '/routes') as $route)
{
    require_once BASE_PATH . '/routes/' . $route;
}
