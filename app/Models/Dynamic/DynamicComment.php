<?php

namespace App\Models\Dynamic;

use App\Models\Model;

class DynamicComment extends Model
{
    protected $primaryKey = 'comment_id';
    protected $is_delete = 0;
}
