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
     * @return Renderable
     */
    public function index(Request $request, Video $videoService)
    {
        // $str = '太阳️*-l0511j_';
        // var_dump(str_replace('_', '', $str));
        // exit;
        $uid = $videoService->getUidByUrl($request->url);
        $path_file_folder = Storage::path('/download');
        $videos_list = $videoService->getVideosByUid($uid, true, $path_file_folder);

        $douyinAuthor = DouyinAuthor::getInstance();
        $douyinVideo = DouyinVideo::getInstance();
        if ($videos_list){
            // 录入作者
            $author = $douyinAuthor->where('uid', $videos_list['author']['uid'])->first();
            if (!$author){
                $author = $douyinAuthor->create(array_merge($videos_list['author'], ['share_url' => $request->url]));
            }
            // 上一次同步时间
            $author->update(['last_sync' => time()]);

            // 录入视频
            foreach ($videos_list['list'] as $video){
                $_data = [
                                             'author_id' => $author->author_id,
                                             'aweme_id' => $video['aweme_id'],
                                             'cover' => $video['cover'],
                                             'desc' => $video['desc'],
                                             'images' => $video['images'],
                                             'video' => [
                                                 // path 视频地址
                                                 // duration 时长
                                                 // width 宽度
                                                 // height 高度
                                                 // ratio
                                                 'play_path' => $video['video_path'],
                                                 'path' => isset($video['real_video_path']) && !empty($video['real_video_path']) ? str_replace($path_file_folder, '', $video['real_video_path']) : $video['video_path'],
                                                 'duration' => $video['duration'],
                                                 'width' => $video['width'],
                                                 'height' => $video['height'],
                                                 'ratio' => $video['ratio'],
                                             ],
                        'statistics' => $video['statistics']
                                         ];
                $detail = $douyinVideo->where('aweme_id', $video['aweme_id'])->first();
                if (!$detail){
                    $douyinVideo->create($_data);
                }else{
                    $detail->update($_data);
                }
            }
        }

        var_dump($videos_list);
        exit;
        return view('douyinvideos::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('douyinvideos::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('douyinvideos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('douyinvideos::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
