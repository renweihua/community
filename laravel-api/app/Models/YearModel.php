<?php

namespace App\Models;

/**
 * App\Models\YearModel
 *
 * @property-read mixed $created_time
 * @property-read mixed $updated_time
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|YearModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YearModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|YearModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @mixin \Eloquent
 */
class YearModel extends MonthModel
{
    const MIN_TABLE       = '2020';// 表名最小的月份
    const MONTH_SUB_TABLE = true; // 开启月分表
    const MONTH_FORMAT    = 'Y';

    public function __construct($data = [])
    {
        parent::__construct($data);
        // 如果年份为空，那么需要设置按月分表表名【默认以当前时间为准】
        if (empty($this->month)) $this->setMonthTable();
    }

    /**
     * 获取从开始分表到明年为止，所有的年份
     *
     * @return array
     */
    public function getAllMonthes(): array
    {
        $years = get_year_range(self::MIN_TABLE . '-01', date('Y-01', strtotime('+1 year')));
        krsort($years);
        return $years;
    }

    /**
     * 设置按年分表
     *
     * @param  string  $month
     *
     * @return $this
     */
    public function setMonthTable(string $month = '')
    {
        $month = empty($month) ? date(self::MONTH_FORMAT) : (strlen($month) == 4 ? $month : date(self::MONTH_FORMAT, strtotime($month)));
        // 替换为日期格式，否则将无法转化为时间戳（有效的日期格式 - 拼接才可以）
        $month = str_replace('_', '-', $month);

        // 当表名大于最小表名时，设置表名。
        // if ( $month >= self::MIN_TABLE ) {}
        $this->month = $month;
        $this->table = $this->getOldTableName() . '_' . $this->month;

        return $this;
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

    /**
     * 获取原始表名（移除年份表的后缀）【包含前缀】
     *
     * @return string
     */
    public function getOldTableName():string
    {
        $table_name = $this->getTable();
        $suffix_len = strlen(self::MIN_TABLE);
        $suffix = substr($table_name, -$suffix_len, $suffix_len);
        // 检测当前表名是否为按年分表的表名
        if (preg_match ("/^([0-9]{4})$/", $suffix, $parts)){
            // 减1是因为默认还有一个下划线
            $table_name = substr($table_name, 0, -$suffix_len - 1);
        }
        return $table_name;
    }
}
