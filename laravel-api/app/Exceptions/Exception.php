<?php

namespace App\Exceptions;

use App\Traits\Json;
use Throwable;

class Exception extends \Exception
{
    use Json;

    protected $msg;

    public function __construct($message = "success", $code = 200, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->msg = $message;
    }
}
