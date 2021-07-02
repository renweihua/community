<?php

declare(strict_types = 1);

namespace App\Constants\Socket;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class SocketConst extends AbstractConstants
{
    /**
     * @Message("Error！")
     */
    const STATUS_ERROR = 0;
    /**
     * @Message("Success！")
     */
    const STATUS_SUCCESS = 1;
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;
    /**
     * @Message("发送成功！")
     */
    const SUCCESS = 200;
    /**
     * @Message("请先登录！")
     */
    const UN_LOGIN = 401;
    /**
     * @Message("请传TOKEN参数！")
     */
    const UN_TOKEN = 10001;
    /**
     * @Message("Token已失效！")
     */
    const TOKEN_INVALID = 10002;
    /**
     * @Message("加入房间成功！")
     */
    const JOIN_ROOM = 11001;
    /**
     * @Message("请重新上传文件！")
     */
    const UPLOAD_UNFILE = 11002;
    /**
     * @Message("文件上传成功！")
     */
    const UPLOAD_SUCCESS = 11003;
    /**
     * @Message("文件上传失败！")
     */
    const UPLOAD_ERROR = 11004;
    /**
     * @Message("消息发送失败！")
     */
    const MESSAGE_SEND_ERROR = 0;
    /**
     * @Message("消息发送成功！")
     */
    const MESSAGE_SEND_SUCCESS = 1;
}
