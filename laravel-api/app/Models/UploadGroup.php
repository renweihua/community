<?php

namespace App\Models;

/**
 * App\Models\UploadGroup
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UploadGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UploadGroup extends Model
{
    protected $primaryKey = 'group_id';
    protected $is_delete = 0;
}
