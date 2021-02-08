<?php

namespace App\Models;

class MonthModel extends Model
{
    const MIN_TABLE    = '2020_12';// 表名最小的月份
    const MONTH_FORMAT = 'Y_m';

    /**
     * @var 当前指定表的月份
     */
    protected $month;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // 如果月份为空，那么需要设置当月表名
        if (empty($this->month)) $this->setMonthTable();
    }

    public function getMonth(): string
    {
        return $this->month;
    }


    /**
     * 设置按月分表
     *
     * @param  string  $month
     *
     * @return $this
     */
    public function setMonthTable(string $month = '')
    {
        $month = empty($month) ? date(self::MONTH_FORMAT) : date(self::MONTH_FORMAT, strtotime($month));
        // 替换为日期格式，否则将无法转化为时间戳（有效的日期格式 - 拼接才可以）
        $month = str_replace('_', '-', $month);

        // 当表名大于最小表名时，依旧设置表名。
        // if ( $month >= str_replace('_', '-', self::MIN_TABLE) ) {}
        $this->month = date(self::MONTH_FORMAT, strtotime($month));
        $this->table = $this->getOldTableName() . '_' . $this->month;

        return $this;
    }

    /**
     * 获取从开始分表到今天为止，所有的月份
     *
     * @return array
     */
    public function getAllMonthes() : array
    {
        $monthes = get_month_range(str_replace('_', '-', self::MIN_TABLE), date('Y-m'));
        krsort($monthes);
        return $monthes;
    }

    /**
     * 获取原始表名（移除月份表的后缀）【包含前缀】
     *
     * @return string
     */
    public function getOldTableName():string
    {
        $table_name = $this->getTable();
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
     * 生成新表
     *
     * @param  string  $new_table
     * @param  string  $time
     * @param  string  $format
     * @param  string  $old_table
     *
     * @return bool
     */
    public function createMonthTable(string $new_table = '', $time = '', $format = self::MONTH_FORMAT, string $old_table = '')
    {
        $new_table = empty($new_table) ? $this->getOldTableName() . '_' . date($format, empty($time) ? time() : $time) : $new_table;

        // 数据表前缀
        $db_prefix = env('DB_PREFIX');

        return $this->setCopyTable($db_prefix . $new_table, $db_prefix . $this->getOldTableName());
    }
}
