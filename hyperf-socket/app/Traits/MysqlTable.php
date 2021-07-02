<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Model\MonthModel;
use Hyperf\DbConnection\Db;

trait MysqlTable
{
    use Error;

    /**
     * 当前表的字段结构
     *
     * @return array
     */
    public function getColumnResult() : array
    {
        return Db::select('SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = ?', [$this->getTableName()]);
    }

    /**
     * 获取表的所有字段
     *
     * @return array
     */
    public function getColumnName() : array
    {
        $res = $this->getColumnResult();
        return array_column($res, 'COLUMN_NAME');
    }

    /**
     * 复制表
     *
     * @param  string  $new_table
     * @param  string  $old_table
     *
     * @return bool
     */
    public function setCopyTable(string $new_table, string $old_table = '')
    {
        if ( empty($new_table) ) {
            $this->setError('请先设置要生成的新表名');
            return false;
        }
        $old_table = empty($old_table) ? $this->getTableName() : $old_table;
        return Db::selectOne("CREATE TABLE IF NOT EXISTS `{$new_table}` LIKE `{$old_table}`");
    }

    // 除了月份表，其实根本用不到此功能；月份表模型已重写此方法。
    public function createMonthTable(string $new_table = '', $time = '', $format = MonthModel::MONTH_FORMAT, string $old_table = '')
    {
        $new_table = empty($new_table) ? $this->getTableName() . '_' . date($format, empty($time) ? time() : $time) : $new_table;
        return $this->setCopyTable($new_table, $old_table);
    }

    /**
     * 查看数据库设置的sql最大长度
     *
     * @return mixed
     */
    public function getSqlLength()
    {
        return Db::selectOne('show variables like \'%max_allowed_packet%\'');
    }

    /**
     * 查看超时设置
     *
     * @return mixed
     */
    public function getTimeout()
    {
        return Db::selectOne("show global variables like '%timeout%'");
    }

    /**
     * 设置超时时间
     *
     * @param  int  $timeout
     *
     * @return mixed
     */
    public function setTimeout(int $timeout = 28800)
    {
        return Db::selectOne("set global interactive_timeout = ?", [$timeout]);
    }

    /**
     * 截断表
     *
     * @param  string  $table_name
     *
     * @return mixed
     */
    public function setTruncate($table_name = '')
    {
        $table_name = empty($table_name) ? $this->getTableName() : $table_name;
        return Db::selectOne("truncate ?", [$table_name]);
    }
}
