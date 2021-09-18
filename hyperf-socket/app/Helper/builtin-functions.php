<?php

use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;

/**
 * 获取Container
 */
if ( !function_exists('di') ) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  null|mixed  $id
     *
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ( $id ) {
            return $container->get($id);
        }
        return $container;
    }
}

if ( !function_exists('container') ) {
    /**
     * 容器示例
     *
     * @return \Psr\Container\ContainerInterface
     */
    function container()
    {
        return ApplicationContext::getContainer();
    }
}

if ( !function_exists('format_throwable') ) {
    /**
     * Format a throwable to string.
     *
     * @param  Throwable  $throwable
     *
     * @return string
     */
    function format_throwable(Throwable $throwable) : string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if ( !function_exists('redis') ) {
    function redis($name = 'default')
    {
        return di()->get(RedisFactory::class)->get($name);
    }
}

/**
 * 获取文件URL
 */
function get_file_url($image){
    return env('API_URL') . '/storage/' . $image;
}

/**
 * 设置文件URL
 */
function set_file_url($image){
    return str_replace(env('API_URL') . '/storage/', '', $image);
}

function cnpscy_config($key, $default = '')
{
    $config = __DIR__ . '/../../laravel-api/config/cnpscy.php';
    $arr = explode('.', $key);
    $value = $default;
    foreach ($arr as $key => $item){
        if (!isset($config[$item])){
            break;
        }else{
            if ($key + 1 < count($arr)){
                $config = $config[$item];
                continue;
            }else{
                $value = $config[$item];
                break;
            }
        }
    }
    return $value;
}