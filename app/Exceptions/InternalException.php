<?php

namespace App\Exceptions;

use Illuminate\Http\Request;

class InternalException extends Exception
{
    public function __construct(string $message, string $msg = '系统内部错误', int $code = 500)
    {
        parent::__construct($message, $code);
        $this->msg = $msg;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->msg], $this->code);
        }
    }
}
