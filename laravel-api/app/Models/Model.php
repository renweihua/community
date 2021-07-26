<?php

namespace App\Models;

use App\Scopes\DeleteScope;
use App\Traits\Instance;
use App\Traits\MysqlTable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * App\Models\Model
 *
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @mixin \Eloquent
 */
class Model extends EloquentModel
{
    use MysqlTable;
    use Instance;
    use HasFactory;
    use Filterable;

    /**
     * 与表关联的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型日期的存储格式：录入时，创建与更新的时间为：时间戳
     *
     * @var string
     */
    protected $dateFormat = 'U';

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    public function getCreatedTimeAttribute()
    {
        return $this->attributes[self::CREATED_AT];
    }

    public function getUpdatedTimeAttribute()
    {
        return $this->attributes[self::UPDATED_AT];
    }

    /**
     * 自定义的软删除
     */
    protected $is_delete = 1; //是否开启删除（1.开启删除，就是直接删除；0.假删除）
    protected $delete_field = 'is_delete'; //删除字段

    public function getIsDelete()
    {
        return $this->is_delete;
    }

    public function getDeleteField()
    {
        return $this->delete_field;
    }

    /**
     * 不可批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 模型的 "booted" 方法
     *
     * 应用全局作用域
     *
     * @return void
     */
    protected static function booted()
    {
        // 假删除的作用域
        static::addGlobalScope(new DeleteScope(new static));
    }

    public static function firstByWhere($where)
    {
        return self::where($where)->first();
    }
}
