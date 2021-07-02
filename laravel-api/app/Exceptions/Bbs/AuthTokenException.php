<?php

namespace App\Exceptions\Bbs;

use App\Exceptions\Exception;
use Illuminate\Http\Request;

class AuthTokenException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            $this->setHttpCode(401);
            return $this->errorJson($this->msg);
        }
    }
}
