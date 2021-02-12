<?php

namespace App\Models\User;

use App\Models\MonthModel;

class UserSign extends MonthModel
{
    protected $primaryKey = 'sign_id';
    protected $is_delete = 0; //是否开启删除（1.开启删除，就是直接删除；0.假删除）
}
