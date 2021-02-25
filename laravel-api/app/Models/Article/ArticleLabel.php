<?php

namespace App\Models\Article;

use App\Constants\CacheKeys;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;

class ArticleLabel extends Model
{
    protected $primaryKey = 'label_id';
    protected $is_delete = 0;

    public function articles()
    {
        return $this->belongsToMany(Article::class,
            ArticleWithLabel::class,
            'label_id',
            'article_id',
            'label_id',
            'article_id')
            ->withPivot([
            'article_id',
            'label_id',
        ]);
    }

    /**
     * 标签缓存数据
     *
     * @return mixed
     */
    public static function getLabelsByCache()
    {
        return Cache::remember(CacheKeys::CACHE_WEB_LABELS, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return self::get();
        });
    }
}
