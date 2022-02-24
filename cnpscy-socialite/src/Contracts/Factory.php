<?php

namespace Cnpscy\Socialite\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \Cnpscy\Socialite\Contracts\Provider
     */
    public function driver($driver = null);
}
