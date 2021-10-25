<?php

namespace App\Model\Dynamic;

use App\Model\Model;
use App\Model\User\UserFollowFan;
use App\Model\User\UserInfo;
use App\Model\User\UserOtherlogin;
use App\Modules\Bbs\Database\factories\DynamicFactory;
use Hyperf\Database\Model\Events\Created;
use Hyperf\Database\Model\Events\Deleted;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

use Hyperf\Database\Model\Events\Saving;

class Dynamic extends Model
{
//    use Searchable;
//
//    /**
//     * 指定索引
//     * 搜索的type
//     *
//     * @return string
//     */
//    public function searchableAs()
//    {
//        return 'dynamics_index';
//    }
//
//    /**
//     * 设置导入索引的数据字段
//     * @return array
//     */
//    public function toSearchableArray()
//    {
//        return [
//            // 'dynamic_id' => $this->dynamic_id,
//            'title'   => $this->dynamic_title,
//            'content' => $this->dynamic_content,
//        ];
//    }
//
//    /**
//     * 指定 搜索索引中存储的唯一ID
//     * @return mixed
//     */
//    public function getScoutKey()
//    {
//        return $this->dynamic_id;
//    }
//
//    /**
//     * 指定 搜索索引中存储的唯一ID的键名
//     * @return string
//     */
//    public function getScoutKeyName()
//    {
//        return $this->primaryKey;
//    }

    protected $primaryKey = 'dynamic_id';
    protected $is_delete  = 0;
    protected $appends = ['time_formatting', 'dynamic_type_text'];

    public function created(Created $event)
    {
        var_dump('Dynamic - created');

        // 新增与删除动态时，调用会员的统计缓存字段
        $this->userInfo->refreshCache();
    }

    public function deleted(Deleted $event)
    {
        var_dump('Dynamic - delete');

        // 新增与删除动态时，调用会员的统计缓存字段
        $this->userInfo->refreshCache();
    }

    public function saving(Saving $event)
    {
        var_dump('Dynamic - saving');

        if ($this->isDirty('dynamic_markdown') && !empty($this->dynamic_markdown)) {
            $this->dynamic_content = self::toHTML($this->dynamic_markdown);
        }
    }

    /**
     * 只查询 启用 的作用域
     */
    public function scopeCheck($query)
    {
        return $query->where('is_check', 1);
    }

    // 统计扩展字段
    const CACHE_EXTENDS_FIELDS = [
        'reads_num' => 0, // 浏览量
        'comments_count' => 0, // 评论数量
        'praises_count' => 0, // 点赞数量
        'collections_count' => 0, // 收藏数量
    ];

    public function getCacheExtendsAttribute()
    {
        return \array_merge(self::CACHE_EXTENDS_FIELDS, json_decode($this->attributes['cache_extends'] ?? '{}', true));
    }

    public function setCacheExtendsAttribute($value)
    {
        $this->attributes['cache_extends'] = json_encode(array_merge(json_decode($this->attributes['cache_extends'] ?? '{}', true), $value));
    }

    // 刷新统计数据
    public function refreshCache()
    {
        $this->update(['cache_extends' => \array_merge(self::CACHE_EXTENDS_FIELDS, [
            'reads_num' => (int)$this->cache_extends['reads_num'],
            'comments_count' => $this->comments()->count(),
            'praises_count' => $this->praises()->count(),
            'collections_count' => $this->collection()->count(),
        ])]);
    }

    /**
     * 获取多图，自动转成数组
     *
     * @param $key
     * @return false|string[]
     */
    public function getDynamicImagesAttribute($key)
    {
        if (empty($key)) return [];
        $imgs = explode(',', $key);
        foreach ($imgs as &$img) {
            if (!check_url($img)){
                $img = get_file_url($img);
            }
        }
        return $imgs;
    }

    /**
     * 设置动态的图片
     *
     * @param $key
     */
    public function setDynamicImagesAttribute($key)
    {
        if ( !empty($key)) {
            $key = explode(',', $key);
            foreach ($key as &$value) {
                $value = str_replace(get_file_url('/'), '', $value);
            }
            $this->attributes['dynamic_images'] = implode(',', $key);
        }
    }

    /**
     * 获取视频地址
     *
     * @param $value
     *
     * @return string
     */
    public function getVideoPathAttribute($value)
    {
        if (empty($value)) return '';
        if (check_url($value)){
            return $value;
        }
        return get_file_url($value);
    }

    /**
     * 设置动态的视频地址
     *
     * @param $value
     */
    public function setVideoPathAttribute($value)
    {
        if ( !empty($value)) {
            $this->attributes['video_path'] = str_replace(get_file_url('/'), '', $value);
        }
    }

    /**
     * 获取视频信息
     *
     * @param $value
     *
     * @return mixed|object
     */
    public function getVideoInfoAttribute($value)
    {
        if (empty($value)) return (object)[];
        return my_json_decode($value);
    }

    /**
     * 设置视频信息
     *
     * @param $value
     */
    public function setVideoInfoAttribute($value)
    {
        $this->attributes['video_info'] = my_json_encode($value);
    }

    // 时间戳格式化
    public function getTimeFormattingAttribute($value)
    {
        if(!isset($this->attributes['created_time'])) return '';
        return formatting_timestamp($this->attributes['created_time']);
    }

    /**
     * 获取动态类型文本
     *
     * @return string
     */
    public function getDynamicTypeTextAttribute(): string
    {
        $text = '动态';
        switch ($this->attributes['dynamic_type']){
            case 1: // 图文
                $text = '图文';
                break;
            case 2: // 视频
                $text = '视频';
                break;
            case 3: // 摄影/相册
                $text = '相册';
                break;
        }
        return $text;
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    public function userOtherLogin()
    {
        return $this->belongsTo(UserOtherlogin::class, 'user_id', 'user_id');
    }

    // 是否收藏
    public function isCollection()
    {
        return $this->hasOne(DynamicCollection::class, $this->primaryKey, $this->primaryKey);
    }

    // 收藏
    public function collection()
    {
        return $this->hasMany(DynamicCollection::class, $this->primaryKey, $this->primaryKey);
    }

    // 是否点赞
    public function isPraise()
    {
        return $this->hasOne(DynamicPraise::class, $this->primaryKey, $this->primaryKey);
    }

    // 点赞
    public function praises()
    {
        return $this->hasMany(DynamicPraise::class, $this->primaryKey, $this->primaryKey);
    }

    // 评论
    public function comments()
    {
        return $this->hasMany(DynamicComment::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * 发布人是谁的关注人
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fanUser()
    {
        return $this->hasOne(UserFollowFan::class, 'friend_id', 'user_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id')->select('topic_id', 'topic_name', 'topic_description', 'topic_cover');
    }

    public static function getListByIds(array $ids)
    {
        $list = self::whereIn('dynamic_id', $ids)->select('dynamic_id', 'dynamic_title', 'dynamic_images', 'dynamic_type', 'created_time')->get()->toArray();
        return array_column($list, null, 'dynamic_id');
    }

    public static function toHTML(string $markdown)
    {
        return app(\ParsedownExtra::class)->text(\emoji($markdown));
    }
}
