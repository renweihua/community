<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Model\Model;

class Version extends Model
{
    protected $primaryKey = 'version_id';
    protected $is_delete  = 0;
}
