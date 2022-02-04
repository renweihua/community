<?php

declare(strict_types = 1);

namespace Cnpscy\DouyinDownload;

use Cnpscy\DouyinDownload\Utils\Ip;

abstract class Server
{
    const SERVER_URL = 'https://www.iesdouyin.com/web/api/v2/';
    protected $http;

    public function __construct()
    {
        $http = new Http;
        // 设置请求IP
        $http = $http->addHeader(Ip::randHeaders());

        $this->http = $http;
    }

    /**
     * 获取会员详情的API的URL
     *
     * @param  string  $sec_uid
     *
     * @return string
     */
    public function getUserUrlBySecUid(string $sec_uid) : string
    {
        return self::SERVER_URL . 'user/info/?sec_uid=' . $sec_uid;
    }

    /**
     * 获取会员的视频列表的API的URL
     *
     * @param  string  $sec_uid
     * @param  int     $max_cursor
     *
     * @return string
     */
    public function getVideosUrlBySecUid(string $sec_uid, int $max_cursor = 0) : string
    {
        return self::SERVER_URL . 'aweme/post/?sec_uid=' . $sec_uid . '&count=2000&max_cursor=' . $max_cursor;
    }

    /**
     * 通过URL获取会员的sec_uid
     *
     * @param  string  $url
     *
     * @return string
     * @throws \Exception
     */
    public function getSecUidByUrl(string $url) : string
    {
        $content = htmlspecialchars($this->http->setMaxFollow(0)->fetch($url));
        preg_match('/(?<=sec_uid=)[A-Za-z0-9-_]+/', $content, $sec_uid);
        return current($sec_uid) ?? '';
    }

    abstract public function getVideosBySecUid(string $sec_uid) : array;

    abstract public function getUserInfoBySecUid(string $sec_uid) : array;

    public function json_encode($data, string $options = '')
    {
        return json_encode($data, empty($options) ? (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $options);
    }
}