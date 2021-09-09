<?php

namespace App\Modules\DouyinVideos\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Douyin\DouyinAuthor;
use App\Modules\DouyinVideos\Jobs\SyncDouyinAuthor;
use App\Traits\Json;
use Cnpscy\DouyinDownload\Video;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DouyinVideosController extends Controller
{
    use Json;

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request, Video $videoService): JsonResponse
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

        return $this->successJson([],
            '队列追加成功....'
            . PHP_EOL . '作者昵称：' . $class->getNickname()
            . PHP_EOL . '作者头像：' . $class->getAvatarThumb()
            . PHP_EOL . '作者sec_uid：' . $class->getSecUid()
        );
    }
}
