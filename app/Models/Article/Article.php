<?php

namespace App\Models\Article;

use App\Constants\CacheKeys;
use App\Models\Bbs\Menu;
use App\Models\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $primaryKey = 'article_id';
    protected $is_delete  = 0;
    // 追加封面图
    protected $appends = ['article_cover'];

    /**
     * 只查询 启用 的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheck($query)
    {
        return $query->where('is_check', 1);
    }

    /**
     * 获取文章的图片
     *
     * @param $key
     *
     * @return string
     */
    public function getArticleCoverAttribute($key)
    {
        if (empty($this->attributes['article_images'])) return '';
        $imgs = explode(',', $this->attributes['article_images']);
        return Storage::url(current($imgs));
    }

    /**
     * 获取多图，自动转成数组
     *
     * @param $key
     * @return false|string[]
     */
    public function getArticleImagesAttribute($key)
    {
        if ( empty($key) ) return [];
        $imgs = explode(',', $key);
        foreach ($imgs as &$img){
            $img = Storage::url($img);
        }
        return $imgs;
    }

    /**
     * 设置文章的图片
     *
     * @param $key
     */
    public function setArticleImagesAttribute($key)
    {
        if ( !empty($key) ) {
            foreach ($key as &$value){
                $value = str_replace(Storage::url('/'), '', $value);
            }
            $this->attributes['article_images'] = implode(',', $key);
        }
    }

    public function category()
    {
        return $this->hasOne(ArticleCategory::class, 'category_id', 'category_id');
    }

    public function labels()
    {
        return $this->belongsToMany(ArticleLabel::class, ArticleWithLabel::class, 'article_id', 'label_id', 'article_id', 'label_id')->withPivot([
            'article_id',
            'label_id',
        ]);
    }

    public function content()
    {
        $primaryKey = $this->getKeyName();
        return $this->hasOne(ArticleContent::class, $primaryKey, $primaryKey);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * 获取有效文章总量的统计
     *
     * @param  bool  $force_update 是否强制更新缓存
     *
     * @return int
     */
    public function cacheStatistics(bool $force_update = false) : int
    {
        $cache_key = CacheKeys::CACHE_ARTICLE_COUNT;
        // 强制更新缓存
        if ($force_update){
            // 删除缓存key
            Cache::forget($cache_key);
        }
        $articles_count = Cache::remember($cache_key, CacheKeys::KEY_DEFAULT_TIMEOUT, function() {
            return $this->check()->count();
        });
        return $articles_count;
    }
}
