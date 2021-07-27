<?php

namespace App\Models;

/**
 * App\Models\TableBackup
 *
 * @property int $backup_id 数据库备份记录表
 * @property int $admin_id 操作人
 * @property string $created_ip 创建IP
 * @property string $tables_name 备份的表名
 * @property int $file_size 文件大小：字节
 * @property int $file_num 文件数量
 * @property string $backup_files 备份的文件
 * @property int $is_delete 是否删除：0.否；1.是
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereBackupFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereBackupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereFileNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereTablesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TableBackup whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class TableBackup extends Model
{
    protected $primaryKey = 'backup_id';
    protected $is_delete = 0;

//    public function getTablesNameAttribute($key)
//    {
//        if (empty($key)) return [];
//        return explode(',', $key);
//    }
}
