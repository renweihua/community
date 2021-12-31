<?php

namespace Cnpscy\DouyinDownload\Traits;

/**
 * Trait Instance
 *
 * 单例
 *
 * @package App\Traits
 */
trait Instance
{
    /**
     * 单例
     */
    protected static $instance;

    static function getInstance(...$args)
    {
        if (empty(self::$instance)) {
            self::$instance = new static(...$args);
        }else{
            if(self::$instance instanceof static){

            }else{
                self::$instance = new static(...$args);
            }
        }
        return self::$instance;
    }
}
