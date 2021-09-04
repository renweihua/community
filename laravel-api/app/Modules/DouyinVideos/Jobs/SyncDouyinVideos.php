<?php

namespace App\Modules\DouyinVideos\Jobs;

use App\Models\Douyin\DouyinVideo;
use App\Services\CosService;
use Cnpscy\DouyinDownload\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncDouyinVideos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $author;
    protected $path_file_folder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($author)
    {
        Log::info('同步抖音的视频信息');
        $this->author = $author;
        // 作者的文件夹目录
        $this->path_file_folder = 'douyin-videos/' . $author->nick_name . '-' .  make_file_folder_name($author->unique_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $douyinVideo = DouyinVideo::getInstance();

        $class = Video::getInstance();

        // 是否开启下载
        $open_download = true;
        $max_cursor = $download_nums = $total = 0;

        do {
            // 获取作者的视频列表【通过作者的sec_uid标识，同步最新未记录的视频】
            $class->getVideosBySecUid($this->author->sec_uid, $max_cursor);
            // 视频列表
            $lists = $class->getResult();
            // 是否还有更多
            $has_more = $class->getHasMore();
            // 开启下载，创建文件夹
            if ( $open_download ) {
                foreach ($lists as $key => &$item) {
                    // 开启下载，下载文件
                    $file_name = empty($item['desc']) ? $item['aweme_id'] : $item['desc'];
                    $file_name = str_replace([
                        '/',
                        '//',
                        '\\',
                    ], '', $file_name);
                    $path_file_name = $this->path_file_folder . '/' . $file_name . '.mp4';
                    // // 下载文件
                    // if ( $item['video_path'] && !is_file($path_file_name) ) {
                    //     file_put_contents($path_file_name, fopen($item['video_path'], 'r'));
                    //     ++$download_nums;
                    // }

                    // 存储到COS
                    // 获取在线文件的大小
                    $res = get_headers($item['video_path'],true);

                    $file_url = CosService::getInstance()->put(
                        file_get_contents($item['video_path']),
                        $file_name . '.mp4',
                        $this->path_file_folder,
                        $res['Content-Length'],
                        'video/mp4',
                        'mp4'
                    );
                    $item['real_video_path'] = $file_url;

                    $data = [
                        'author_id'  => $this->author->author_id,
                        'aweme_id'   => $item['aweme_id'],
                        'cover'      => $item['cover'],
                        'desc'       => $item['desc'],
                        'images'     => $item['images'],
                        'video'      => [
                            'play_path' => $item['video_path'],
                            // path 视频地址
                            'path'      => isset($item['real_video_path']) && !empty($item['real_video_path']) ? str_replace($this->path_file_folder, '', $item['real_video_path']) : $item['video_path'],
                            // duration 时长
                            'duration'  => $item['duration'],
                            // width 宽度
                            'width'     => $item['width'],
                            // height 高度
                            'height'    => $item['height'],
                            'ratio'     => $item['ratio'],
                        ],
                        'statistics' => $item['statistics'],
                    ];
                    $detail = $douyinVideo->where('aweme_id', $item['aweme_id'])->lock(true)->first();
                    if ( !$detail ) {
                        $douyinVideo->create($data);
                    } else {
                        $detail->update($data);
                        // 以最后一条视频为准，判断是否存在入库，入库之后，则不继续请求获取视频列表【因为存在置顶视频，只能以最后一条来验证】
                        if ( $key == count($lists) ) {
                            $has_more = false;
                        }
                    }
                }
            }
            // 获取下一组数据的标识
            $max_cursor = $class->getMaxCursor();
        } while ( $has_more );
    }
}
