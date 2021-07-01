<?php

namespace App\Models;

class TableBackup extends Model
{
    protected $primaryKey = 'backup_id';
    protected $is_delete = 0;

//    public function getTablesNameAttribute($key)
//    {
//        if (empty($key)) return [];
//        return explode(',', $key);
//    }
}
