<?php

namespace App\Modules\DouyinVideos\Http\Controllers;

use App\Models\Douyin\DouyinAuthor;
use App\Models\Douyin\DouyinVideo;
use Cnpscy\DouyinDownload\Video;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class DouyinVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request, Video $videoService)
    {
        $class = new $videoService;
        $url = $request->url ?? 'https://v.douyin.com/dNdR5W7/';
        $uid = $class->getSecUidByUrl($url);

        // 是否开启下载
        $open_download = true;
        // 下载的文件路径
        $path_file_folder = Storage::path('/download');
        // var_dump($path_file_folder);

        $max_cursor = $download_nums = $total = 0;

        $douyinAuthor = DouyinAuthor::getInstance();
        $douyinVideo = DouyinVideo::getInstance();
        do {
            $class->getVideosBySecUid($uid, $max_cursor);
            // 作者信息
            // $author = $class->getAuthor();
            // 视频列表
            $lists = $class->getResult();
            // 开启下载，创建文件夹
            if ($open_download){
                // 作者的文件夹目录创建一次即可
                if ($max_cursor == 0){
                    $path_file_folder = trim($path_file_folder, '/');
                    $path_file_folder .= '/' . $class->getNickname() . '-' .  make_file_folder_name($class->getUniqueId());
                    if (!is_dir($path_file_folder)) {
                        mkdir($path_file_folder, 0777, true);
                    }
                }

                // 录入作者
                $author = $douyinAuthor->where('uid', $class->getUid())->first();
                if ( !$author ) {
                    $author = $douyinAuthor->create(array_merge($class->getAuthor(), ['share_url' => $url]));
                }
                // 上一次同步时间
                $author->update(['last_sync' => time()]);

                foreach ($lists as $key => &$item){
                    // 开启下载，下载文件
                    $file_name = empty($item['desc']) ? $item['aweme_id'] : $item['desc'];
                    $file_name = str_replace(['/','//','\\'], '', $file_name);
                    $path_file_name = $path_file_folder . '/' . $file_name .'.mp4';
                    // 下载文件
                    if ($item['video_path'] && !is_file($path_file_name)){
                        file_put_contents($path_file_name, fopen($item['video_path'], 'r'));
                        ++$download_nums;
                    }
                    $item['real_video_path'] = $path_file_name;

                    $data = [
                        'author_id'  => $author->author_id,
                        'aweme_id'   => $item['aweme_id'],
                        'cover'      => $item['cover'],
                        'desc'       => $item['desc'],
                        'images'     => $item['images'],
                        'video'      => [
                            // path 视频地址
                            // duration 时长
                            // width 宽度
                            // height 高度
                            // ratio
                            'play_path' => $item['video_path'],
                            'path'      => isset($item['real_video_path']) && !empty($item['real_video_path']) ? str_replace($path_file_folder, '', $item['real_video_path']) : $item['video_path'],
                            'duration'  => $item['duration'],
                            'width'     => $item['width'],
                            'height'    => $item['height'],
                            'ratio'     => $item['ratio'],
                        ],
                        'statistics' => $item['statistics'],
                    ];
                    $detail = $douyinVideo->where('aweme_id', $item['aweme_id'])->first();
                    if ( !$detail ) {
                        $douyinVideo->create($data);
                    } else {
                        $detail->update($data);
                        // 以最后一条视频为准，判断是否存在入库，入库之后，则不继续请求获取视频列表【因为存在置顶视频，只能以最后一条来验证】
                        if ($key == count($lists)){
                            $has_more = false;
                        }
                    }
                }
            }
            // $response['has_more'] && $max_cursor

            // 是否还有更多
            $has_more = $class->getHasMore();
            // 获取下一组数据的标识
            $max_cursor = $class->getMaxCursor();
        }while($has_more);

        var_dump('总视频量：' . $class->getTotal());
        var_dump('本地下载视频量：' . $download_nums);
        exit;
        return view('douyinvideos::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('douyinvideos::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     *
     * @return Renderable
     */
    public function show($id)
    {
        return view('douyinvideos::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Renderable
     */
    public function edit($id)
    {
        return view('douyinvideos::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int      $id
     *
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
