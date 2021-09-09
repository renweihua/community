<?php

namespace App\Modules\DouyinVideos\Http\Controllers;

use App\Models\Douyin\DouyinVideo;
use App\Models\UploadFile;
use Illuminate\Http\JsonResponse;

class IndexController extends DouyinVideosController
{
    /**
     * 随机的推荐视频列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommend(): JsonResponse
    {
        $lists = DouyinVideo::with(
            [
                'author' => function($query){
                    $query->select(['author_id', 'sec_uid', 'uid', 'unique_id', 'nick_name', 'avatar_thumb', 'share_url']);
                }
            ])
            ->select(['video_id', 'author_id', 'aweme_id', 'cover', 'desc', 'images', 'video', 'statistics'])
            ->orderByRaw("RAND()")
            ->limit(10)
            ->get();
        // $string = 'https://bbs-1252866470.cos.ap-shanghai.myqcloud.com//';
        // $uploadFile = UploadFile::getInstance();
        // foreach ($lists as $item){
        //     if (strpos($item->video['path'], $string) !== false){
        //         if ($file = $uploadFile->where('file_name', 'LIKE', '%' . str_replace($string, '', $item->video['path']) . '%')->first()){
        //             $item->update([
        //                 'video->path' => $file->file_url
        //             ]);
        //             var_dump($file->file_url);
        //         }
        //     }
        // }
        return $this->successJson($lists);
    }
}
