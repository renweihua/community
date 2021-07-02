<?php

namespace App\Exceptions\Bbs;

use App\Exceptions\Exception;
use App\Models\Log\UserLoginLog;
use Illuminate\Http\Request;

class AuthException extends Exception
{
    protected $user_id = 0;

    public function __construct(string $message = '', int $code = 0, int $user_id = 0)
    {
        parent::__construct($message, $code);
        $this->user_id = $user_id;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            // 登录日志
            UserLoginLog::getInstance()->add($this->user_id, 0, $this->msg);

            $this->setHttpCode(401);
            return $this->errorJson($this->msg);
        }
    }
}
