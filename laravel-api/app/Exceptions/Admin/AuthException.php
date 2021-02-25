<?php

namespace App\Exceptions\Admin;

use App\Exceptions\Exception;
use App\Models\Log\AdminLoginLog;
use Illuminate\Http\Request;

class AuthException extends Exception
{
    protected $admin_id = 0;
    public function __construct(string $message = "", int $code = 0, int $admin_id = 0)
    {
        parent::__construct($message, $code);
        $this->admin_id = $admin_id;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            // 登录日志
            AdminLoginLog::getInstance()->add($this->admin_id, 0, $this->msg);

            $this->setHttpCode(401);
            return $this->errorJson($this->msg);
        }
    }
}
