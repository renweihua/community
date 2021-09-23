<?php

declare(strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Exception;

use App\Traits\Json;

class Exception extends \Exception
{
    use Json;

    public function __construct(string $message = null, $code = 0)
    {
        parent::__construct($message, $code = 0);
    }
}
