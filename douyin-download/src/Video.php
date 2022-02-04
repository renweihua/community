<?php

declare(strict_types = 1);

namespace Cnpscy\DouyinDownload;

use Cnpscy\DouyinDownload\Abstracts\AbstractResponse;

class Video extends AbstractResponse
{
    public function getUserInfoBySecUid(string $sec_uid) : array
    {
        $url = $this->getUserUrlBySecUid($sec_uid);
        // 请求视频接口获取数据
        $response = json_decode($this->http->setMethod('GET')->setMaxFollow(1)->fetch($url), true)['user_info'] ?? [];
        if ( !$response ) {
            return $this->setAuthor();
        }
        return $this->setAuthor($this->getFormatAuthor(array_merge($response, ['sec_uid' => $sec_uid])));
    }

    /**
     * 通过作者的sec_uid获取视频列表数据
     *
     * @param  string  $sec_uid
     * @param  int     $max_cursor
     *
     * @return array
     * @throws \Exception
     */
    public function getVideosBySecUid(string $sec_uid, int $max_cursor = 0) : array
    {
        // 作者标识变更时，数据重置清空
        if ( $this->sec_uid != $sec_uid ) {
            $this->initResponse();
        }
        if ( $max_cursor == -1 ) { // 类库标记暂无数据
            return $this->setResponse();
        }
        if ( $max_cursor != 0 && !$this->getHasMore() ) { // 类库标记暂无更多数据
            return $this->setResponse();
        }
        $url = $this->getVideosUrlBySecUid($sec_uid, $max_cursor);
        $this->sec_uid = $sec_uid;
        // 请求视频接口获取数据
        $response = json_decode($this->http->setMethod('GET')->setMaxFollow(1)->fetch($url), true);
        if ( !$response ) {
            return $this->setResponse();
        }
        return $this->setResponse($response);
    }
}