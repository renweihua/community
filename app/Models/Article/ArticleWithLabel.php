<?php

namespace App\Models\Article;

use App\Models\Model;

class ArticleWithLabel extends Model
{
    protected $primaryKey = 'with_id';
    public $timestamps = false;
}
