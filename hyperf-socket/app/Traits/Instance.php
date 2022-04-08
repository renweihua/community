<?php

declare(strict_types = 1);

namespace App\Traits;

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
        return new static(...$args);
        if ( !isset(self::$instance) ) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

    protected function __construct(){}

    protected function __clone(){}
}
