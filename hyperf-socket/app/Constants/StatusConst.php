<?php

declare(strict_types = 1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * 状态码的定义
 *
 * @Constants
 */
class StatusConst extends AbstractConstants
{
    /**
     * @Message("error！")
     */
    const ERROR = 0;

    /**
     * @Message("success！")
     */
    const SUCCESS = 1;

    /**
     * @Message("无权限！")
     */
    const UN_RABC = -2;

    /**
     * @Message("请重新登录！")
     */
    const UN_LOGIN = -1;
}
