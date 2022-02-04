<?php

namespace Cnpscy\Socialite\Facades;

use Illuminate\Support\Facades\Facade;
use Cnpscy\Socialite\Contracts\Factory;

/**
 * @method static \Cnpscy\Socialite\Contracts\Provider driver(string $driver = null)
 * @see \Cnpscy\Socialite\SocialiteManager
 */
class Socialite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
