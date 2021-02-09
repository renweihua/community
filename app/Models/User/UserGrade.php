<?php

namespace App\Models\User;

use App\Models\Model;
use App\Modules\Bbs\Database\factories\UserGradeFactory;

class UserGrade extends Model
{
    protected static function newFactory()
    {
        return UserGradeFactory::new();
    }
}
