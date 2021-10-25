<?php

namespace App\Model\System;

use App\Model\Model;

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
        return get_file_url($key);
    }

    /**
     * 设置启动图
     *
     * @param $key
     */
    public function setDiagramCoverAttribute($key)
    {
        if ( !empty($key)) {
            $this->attributes['diagram_cover'] = set_file_url($key);
        }
    }
}
