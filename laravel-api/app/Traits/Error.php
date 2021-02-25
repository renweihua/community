<?php

declare(strict_types = 1);

namespace App\Traits;

trait Error
{
    protected $error = 'success';
    protected $status = 1;

    public function getError() : string
    {
        return $this->error;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function setError(string $error, int $status = 0) : void
    {
        $this->error = $error;
        $this->status = $status;
    }

    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }
}
