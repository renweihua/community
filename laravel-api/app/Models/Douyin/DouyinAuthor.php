<?php

namespace App\Models\Douyin;

use App\Models\Model;

class DouyinAuthor extends Model
{
    protected $primaryKey = 'author_id';
    protected $is_delete = 0;

    protected $casts = [
        'original_author' => 'array', // 作者原始数据
    ];
}
