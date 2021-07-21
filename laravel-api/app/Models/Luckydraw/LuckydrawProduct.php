<?php

namespace App\Models\Luckydraw;

use App\Models\Model;

/**
 * App\Models\Luckydraw\LuckydrawProduct
 *
 * @property int $product_id 产品Id
 * @property string $product_name 产品名称
 * @property string $product_cover 产品封面图
 * @property int $product_stock 产品库存
 * @property int $sales_count 销量
 * @property int $on_sale 是否在售：0.否；1.是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除：0.否；1.是
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereProductCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereProductStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereSalesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LuckydrawProduct whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class LuckydrawProduct extends Model
{
    protected $primaryKey = 'product_id';
    protected $is_delete = 0;
}
