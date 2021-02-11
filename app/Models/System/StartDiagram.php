<?php

namespace App\Models\System;

use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class StartDiagram extends Model
{
    protected $is_delete = 0;

    /**
     * 获取启动图
     *
     * @param $key
     *
     * @return false|string[]
     */
    public function getDiagramCoverAttribute($key)
    {
        if (empty($key)) return '';
        return Storage::url($key);
    }

    /**
     * 设置启动图
     *
     * @param $key
     */
    public function setDiagramCoverAttribute($key)
    {
        if ( !empty($key)) {
            $this->attributes['diagram_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }
}
