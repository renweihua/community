<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class UploadFile extends Model
{
    protected $primaryKey = 'file_id';
    protected $is_delete = 0;

    protected $appends = ['file_url'];

    public function getFileUrlAttribute($key)
    {
        if ($this->attributes['storage'] == 'local'){
            return Storage::url($this->attributes['file_name']);
            // return env('APP_URL') . '/' . trim($this->attributes['file_name'],'/');
        }
        return $this->attributes['host_url'] . '/' . trim($this->attributes['file_name'],'/');
    }
}
