<?php

namespace App\Models\Dynamic;

use App\Models\Model;
use App\Models\User\UserFollowFan;
use App\Models\User\UserInfo;
use App\Models\User\UserOtherlogin;
use App\Modules\Bbs\Database\factories\DynamicFactory;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

/**
 * App\Models\Dynamic\Dynamic
 *
 * @property int $dynamic_id 动态表
 * @property int $user_id 会员Id
 * @property int $topic_id 话题/荟吧 Id
 * @property string $dynamic_title 标题
 * @property false|string[] $dynamic_images 多图
 * @property string $video_path 视频地址
 * @property mixed|object $video_info 视频信息（JSON）
 * @property string|null $dynamic_content 动态内容
 * @property int $is_check 是否审核：0：待审核；1：通过；2.拒绝
 * @property int $is_public 公开度：0.私密；1.完全公开；2.密码访问
 * @property string $access_password 对于公开度的“密码访问”设置的密码
 * @property int $is_delete 是否删除
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property string $created_ip 创建时的IP
 * @property string $browser_type 创建时浏览器类型
 * @property int $dynamic_type 动态类型：0.图文；1.视频
 * @property mixed|null $cache_extends 统计的扩展字段
 * @property string $content_type 内容的格式：html；markdown
 * @property string|null $dynamic_markdown 动态内容
 * @property int $excellent_time 精选标记时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dynamic\DynamicCollection[] $collection
 * @property-read int|null $collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dynamic\DynamicComment[] $comments
 * @property-read int|null $comments_count
 * @property-read UserFollowFan|null $fanUser
 * @property-read mixed $time_formatting
 * @property-read \App\Models\Dynamic\DynamicCollection|null $isCollection
 * @property-read \App\Models\Dynamic\DynamicPraise|null $isPraise
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dynamic\DynamicPraise[] $praises
 * @property-read int|null $praises_count
 * @property-read \App\Models\Dynamic\Topic $topic
 * @property-read UserInfo $userInfo
 * @property-read UserOtherlogin $userOtherLogin
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic check()
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereAccessPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereBrowserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereCacheExtends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereDynamicType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereExcellentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereVideoInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dynamic whereVideoPath($value)
 * @mixin \Eloquent
 */
class Dynamic extends Model
{
    use Filterable;

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
    protected $appends = ['time_formatting'];

    protected static function boot()
    {
        parent::boot();

        // 新增与删除动态时，调用会员的统计缓存字段
        $saveContent = function (self $dynamic) {
            $dynamic->userInfo->refreshCache();
        };

        static::created($saveContent);

        static::deleted($saveContent);

        static::saving(function ($content) {
            if ($content->isDirty('dynamic_markdown') && !empty($content->dynamic_markdown)) {
                $content->dynamic_content = self::toHTML($content->dynamic_markdown);
            }

            // $content->body = Purifier::clean($content->body);
        });

        // static::saved(function ($content) {
        //     \dispatch(new FetchContentMentions($content));
        // });
    }

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

    protected static function newFactory()
    {
        return DynamicFactory::new();
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
            $img = Storage::url($img);
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
                $value = str_replace(Storage::url('/'), '', $value);
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
        return Storage::url($value);
    }

    /**
     * 设置动态的视频地址
     *
     * @param $value
     */
    public function setVideoPathAttribute($value)
    {
        if ( !empty($value)) {
            $this->attributes['video_path'] = str_replace(Storage::url('/'), '', $value);
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
        return formatting_timestamp($this->attributes['created_time']);
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
        $list = self::whereIn('dynamic_id', $ids)->select('dynamic_id', 'dynamic_title', 'dynamic_images', 'dynamic_type')->get()->toArray();
        return array_column($list, null, 'dynamic_id');
    }

    public static function toHTML(string $markdown)
    {
        return app(\ParsedownExtra::class)->text(\emoji($markdown));
    }
}
