<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait MysqlTable
{
    use Error;

    /**
     * 复制表
     *
     * @param  string  $new_table
     * @param  string  $old_table
     *
     * @return bool
     */
    public function setCopyTable(string $new_table, string $old_table)
    {
        if ( empty($new_table) ) {
            $this->setError('请先设置要生成的新表名');
            return false;
        }
        return DB::select("CREATE TABLE IF NOT EXISTS `{$new_table}` LIKE `{$old_table}`");
    }

    public function detail(int $id, string $filed = '*', bool $lock = false, array $with = [], array $withCount = [])
    {
        return $this->select($filed)
            ->lock($lock)
            ->with($with)
            ->withCount($withCount)
            ->find($id);
    }

    /**
     * 过滤移除非当前表的字段参数
     *
     * @param  array  $params
     *
     * @return array
     */
    public function setFilterFields(array $params) : array
    {
        $fields = Schema::getColumnListing($this->getTable());
        foreach ($params as $key => $param) {
            if ( !in_array($key, $fields) ) unset($params[$key]);
        }
        // 同时过滤时间戳字段【时间戳只允许自动更改，不允许手动设置】
        if ( $this->timestamps === true && isset($params[self::CREATED_AT]) ) unset($params[self::CREATED_AT]);
        if ( $this->timestamps === true && isset($params[self::UPDATED_AT]) ) unset($params[self::UPDATED_AT]);
        return $params;
    }
}
