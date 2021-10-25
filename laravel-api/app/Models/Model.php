<?php

namespace App\Models;

use App\Models\SoftDelete\SoftDelete;
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
    use SoftDelete;
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
     * 不可批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    public static function firstByWhere($where)
    {
        return self::where($where)->first();
    }
}
