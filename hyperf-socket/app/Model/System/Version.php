<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Model\Model;

class Version extends Model
{
    protected $primaryKey = 'version_id';
    public    $is_delete    = 0;// 是否删除：0.假删除；1.真删除【默认全部假删除】
}
