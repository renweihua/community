<?php

declare(strict_types = 1);

namespace App\Library;

class ExecutionStartTime
{
    protected $start_time;

    public function __construct()
    {
        $this->start_time = microtime(true);
    }

    public function getStartTime()
    {
        return $this->start_time;
    }
}
