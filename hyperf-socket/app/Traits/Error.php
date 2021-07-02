<?php

declare(strict_types = 1);

namespace App\Traits;

trait Error
{
    protected $error = 'success';

    public function getError() : string
    {
        return $this->error;
    }

    public function setError($error) : void
    {
        $this->error = $error;
    }
}
