<?php

namespace Cnpscy\DouyinDownload\Abstracts;

use Cnpscy\DouyinDownload\Interfaces\InterfaceAuthor;
use Cnpscy\DouyinDownload\Interfaces\InterfaceResponse;
use Cnpscy\DouyinDownload\Server;
use Cnpscy\DouyinDownload\Traits\Instance;
use Cnpscy\DouyinDownload\Traits\TraitAuthor;

abstract class AbstractResponse extends Server implements InterfaceResponse, InterfaceAuthor
{
    use TraitAuthor;
    use Instance;

    // 是否有更多数据
    protected $has_more = false;

    public function getHasMore() : bool
    {
        return $this->has_more;
    }

    public function setHasMore(bool $has_more = false) : void
    {
        $this->has_more = $has_more;
    }

    // 获取接口数据时的时间戳标识
    protected $max_cursor = 0;

    public function getMaxCursor() : int
    {
        return $this->max_cursor;
    }

    public function setMaxCursor(int $max_cursor = 0) : void
    {
        $this->max_cursor = $max_cursor;
    }

    /**
     * 获取视频的总量
     *
     * @return int
     */
    public function getTotal() : int
    {
        return count($this->getResult());
    }

    // 列表数据
    protected $list_data = [];

    public function getResult() : array
    {
        return array_values(array_filter($this->list_data));
    }

    public function setResult(array $item) : void
    {
        $this->setIsMerge(true);
        $this->setOriginalData(isset($item['original']) ? $item['original'] : []);
        if (!$this->getIsMerge()){
            $this->list_data = [];
        }
        $this->list_data[] = $item;
    }

    // 原始数据
    protected $original_data = [];

    public function getOriginalData() : array
    {
        return array_values(array_filter($this->original_data));
    }

    public function setOriginalData(array $item) : void
    {
        if (!$this->getIsMerge()){
            $this->original_data = [];
        }
        $this->original_data[] = $item;
    }

    // 是否启用数据合并
    protected $is_merge = true;

    public function setIsMerge(bool $is_merge = true) : self
    {
        $this->is_merge = $is_merge;
        return $this;
    }

    public function getIsMerge() : bool
    {
        return $this->is_merge;
    }

    /**
     * 初始化
     */
    protected function initResponse() : void
    {
        $this->setHasMore(true);
        $this->setIsMerge(false);
        $this->setMaxCursor(0);
        $this->setResult([]);
        $this->setOriginalData([]);
        $this->setAuthor([]);
    }

    protected function setResponse(array $result = []) : array
    {
        return $this->setFormatData($result);
    }

    /**
     * 格式化的作者资料
     *
     * @param $aweme_author
     *
     * @return array
     */
    public function getFormatAuthor($aweme_author): array
    {
        return [
            'uid'             => $aweme_author['uid'],
            'sec_uid'         => $aweme_author['sec_uid'],
            'unique_id'       => empty($aweme_author['unique_id']) ? $aweme_author['uid'] : $aweme_author['unique_id'],
            // 昵称
            'nick_name'       => $aweme_author['nickname'],
            // 签名
            'signature'       => $aweme_author['signature'],
            'follower_count'  => $aweme_author['follower_count'],
            'total_favorited' => $aweme_author['total_favorited'],
            // 头像
            'avatar_thumb'    => current($aweme_author['avatar_thumb']['url_list']),
            // 原作者数据
            'original_author' => $aweme_author,
        ];
    }

    private function setFormatData($response)
    {
        if ( isset($response['aweme_list']) ) {
            // 获取作者的信息
            if ( $response['aweme_list'] && empty($this->getAuthor()) ) {
                $aweme_author = current($response['aweme_list'])['author'];
                $this->setAuthor($this->getFormatAuthor($aweme_author));
            }
            // var_dump(count($response['aweme_list']));
            foreach ($response['aweme_list'] as $item) {
                if ($item){
                    $this->setResult([
                        'sec_uid'         => $item['author']['sec_uid'],
                        'uid'             => $item['author']['uid'],
                        'aweme_id'        => $item['aweme_id'],
                        // 视频Id
                        'cover'           => current($item['video']['origin_cover']['url_list']),
                        // 封面图
                        'video_path'      => $item['video']['play_addr']['url_list'][0] ?? $item['video']['play_addr_lowbr']['url_list'][0],
                        'duration'        => $item['video']['duration'],
                        // 时长
                        'width'           => $item['video']['width'],
                        'height'          => $item['video']['height'],
                        'ratio'           => $item['video']['ratio'],
                        'statistics'      => $item['statistics'],
                        'desc'            => $item['desc'],
                        'images'          => $item['images'],
                        'long_video'      => $item['long_video'],
                        'real_video_path' => '',
                        // 原视频数据
                        'original'        => $item,
                    ]);
                }
            }

            // 是否还有更多数据
            $this->setHasMore(empty($response['aweme_list']) ? false : ($response['has_more'] ?? false));

            // 下一页的标识
            $this->setMaxCursor($response['max_cursor']);
        } else {
            // 是否还有更多数据
            $this->setHasMore(false);

            // 下一页的标识
            $this->setMaxCursor(-1);
        }

        return [
            'total'      => $this->getTotal(),
            'author'     => $this->getAuthor(),
            'list'       => $this->getResult(),
            // 是否有更多
            'has_more'   => $this->getHasMore(),
            // 下一页的标识
            'max_cursor' => $this->getMaxCursor(),
        ];
    }
}