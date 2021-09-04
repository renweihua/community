<?php

namespace App\Modules\DouyinVideos\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Douyin\DouyinAuthor;
use App\Modules\DouyinVideos\Jobs\SyncDouyinAuthor;
use Cnpscy\DouyinDownload\Video;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DouyinVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request, Video $videoService)
    {
        $url = $request->url;
        if (!$url){
            throw new InvalidRequestException('请设置有效的抖音作者分享URL！');
        }

        // 检测数据库是否存在此作者的分享链接
        if (DouyinAuthor::where('share_url', $url)->first()){
            throw new InvalidRequestException('此作者已录入，系统会定期同步视频！');
        }

        $class = new $videoService;

        $sec_uid = $class->getSecUidByUrl($url);

        $class->getUserInfoBySecUid($sec_uid);

        SyncDouyinAuthor::dispatch(array_merge($class->getAuthor(), ['share_url' => $url]))
                        ->delay(now()->addMinutes(rand(1, 10))) // 延迟分钟数
                        ->onConnection('database') // job 存储的服务：当前存储mysql
                        ->onQueue('douyin-queue'); // douyin-queue 队列

        var_dump('队列追加成功……');
        echo PHP_EOL . PHP_EOL . PHP_EOL;

        var_dump('作者昵称：' . $class->getNickname());
        echo PHP_EOL;
        var_dump('作者头像：' . $class->getAvatarThumb());
        echo PHP_EOL;
        var_dump('作者sec_uid：' . $class->getSecUid());
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
