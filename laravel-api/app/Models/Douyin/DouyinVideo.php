<?php

namespace App\Models\Douyin;

use App\Models\Model;

class DouyinVideo extends Model
{
    protected $primaryKey = 'video_id';
    protected $is_delete = 0;
    protected $casts = [
        'images' => 'array', // 多图
        'video' => 'array', // 视频信息
        'statistics' => 'array', // 视频统计信息
    ];
}
