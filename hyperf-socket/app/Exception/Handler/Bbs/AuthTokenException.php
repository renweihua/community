<?php

namespace App\Exception\Handler\Bbs;

use App\Exception\Exception;

class AuthTokenException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        $this->setHttpCode(401);
        return $this->errorJson($this->msg);
    }
}
