<?php

declare (strict_types = 1);

namespace App\Model;

/**
 */
class MonthModel extends Model
{
    const MIN_TABLE       = '2020_07';// 表名最小的月份
    const MONTH_SUB_TABLE = true; // 开启月分表
    const MONTH_FORMAT    = 'Y_m';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // 自动设置按月分表表名【默认以当前时间为准】
        $this->setMonthTable();
    }

    /**
     * 设置按月分表
     *
     * @param  string  $month
     * @param  string  $model
     */
    public function setMonthTable(string $month = '')
    {
        $month = empty($month) ? date(self::MONTH_FORMAT) : $month;
        // 替换为日期格式，否则将无法转化为时间戳（有效的日期格式 - 拼接才可以）
        $month = str_replace('_', '-', $month);

        // 当表名大于最小表名时，设置表名。
        if ( $month > str_replace('_', '-', self::MIN_TABLE) ) {
            $this->setTable($this->getOldTable() . '_' . date(self::MONTH_FORMAT, strtotime($month)));
        }
        return $this;
    }

    /**
     * 获取原始表名（移除月份表的后缀）【不包含前缀】
     *
     * @return string
     */
    public function getOldTable():string
    {
        return str_replace(env('DB_PREFIX'), '', $this->getOldTableName());
    }

    /**
     * 获取原始表名（移除月份表的后缀）【包含前缀】
     *
     * @return string
     */
    public function getOldTableName():string
    {
        $table_name = $this->getTableName();
        $suffix_len = strlen(self::MIN_TABLE);
        $suffix = substr($table_name, -$suffix_len, $suffix_len);
        // 检测当前表名是否为按月分表的表名
        if (preg_match ("/^([0-9]{4})_([0-9]{2})$/", $suffix, $parts)){
            // 减1是因为默认还有一个下划线
            $table_name = substr($table_name, 0, -$suffix_len - 1);
        }
        return $table_name;
    }

    /**
     * 生成新表【月份表特殊，重写此方法】
     *
     * @param  string  $new_table
     * @param  string  $time
     * @param  string  $format
     * @param  string  $old_table
     *
     * @return bool
     */
    public function createMonthTable(string $new_table = '', $time = '', $format = MonthModel::MONTH_FORMAT, string $old_table = '')
    {
        $new_table = empty($new_table) ? $this->getOldTableName() . '_' . date($format, empty($time) ? time() : $time) : $new_table;
        return $this->setCopyTable($new_table, $this->getOldTableName());
    }
}