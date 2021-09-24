<?php

namespace App\Model\System;

use App\Model\Model;

/**
 * App\Model\System\Protocol
 *
 * @property int $protocol_id 协议表
 * @property string $protocol_title 协议标题
 * @property int $protocol_category 协议类型
 * @property string|null $protocol_content 协议内容
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除（0.未删除；1.已删除）
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereProtocolCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereProtocolContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereProtocolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereProtocolTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Protocol whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Protocol extends Model
{
    protected $primaryKey = 'protocol_id';
    protected $is_delete = 0;
}
