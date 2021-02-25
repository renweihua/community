<?php

declare(strict_types = 1);

namespace App\Traits;

use Hyperf\DbConnection\Db;

/**
 * Trait ModelFunctions
 *
 * 定义模型常用的方法
 *
 * @package App\Traits
 */
trait ModelFunctions
{
    public function deleteWhere()
    {
        if ( $this->is_delete == 0 ){
            return self::query()->where($this->delete_field, 0);
        }
        return self::query();
    }

    /**
     * 当前模型的表名全称
     *
     * @return string
     */
    public function getTableName() : string
    {
        return env('DB_PREFIX') . $this->getTable();
    }

    /**
     * 自动设置表名
     *
     * @param $model
     *
     * @return mixed
     */
    public function setTableName()
    {
        // 数据表前缀
        $prefix = env('DB_PREFIX');
        $table_name = $this->getTable();
        if ( empty($table_name) ) {
            // 数据表名 按照模型转化成 下划线+小写
            $model_fie_name = explode('\\', get_called_class());
            $tableName = $prefix . strtolower(string_underscore_lowercase(end($model_fie_name))); // 如果表名存在大写，那么自动转换成：下划线+小写

            // 设置数据表名必须为复数
            $this->setTable(get_string_pluralize($tableName)); // 复数
        }
        // 设定数据表名必须追加前缀
        if ( strpos($this->getTable(), $prefix) === false ) $this->setTable($prefix . $this->getTable());
        return $this;
    }

    /**
     * 获取创建时间
     *
     * @param $value
     *
     * @return false|int|string
     */
    public function getCreatedTimeAttribute($value)
    {
        if ( is_numeric($value) ) return $value;
        return strtotime($value);
    }

    /**
     * 获取更新时间
     *
     * @param $value
     *
     * @return false|int|string
     */
    public function getUpdatedTimeAttribute($value)
    {
        if ( is_numeric($value) ) return $value;
        return strtotime($value);
    }

    /**
     * 过滤录入数据表时，移除非表字段的数据
     *
     * @param $params
     *
     * @return array
     */
    protected function setFilterFields($params) : array
    {
        $fields = $this->getColumnName();
        foreach ($params as $key => $param) {
            if ( !in_array($key, $fields) ) unset($params[$key]);
        }
        // 同时过滤时间戳字段【时间戳只允许自动更改，不允许手动设置】
        if ( $this->timestamps === true && isset($params[self::CREATED_AT]) ) unset($params[self::CREATED_AT]);
        if ( $this->timestamps === true && isset($params[self::UPDATED_AT]) ) unset($params[self::UPDATED_AT]);
        return $params;
    }

    /**
     * 获取详情
     *
     * @param $id
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Model\Model|null
     */
    public static function find($id, $with = [])
    {
        $model = new static();
        $delete_where = $model->is_delete == 0 ? [$model->delete_field => 0] : [];
        return self::query()->where($delete_where)->with($with)->find($id);
    }

    /**
     * 通过where条件获取详情，并且检测结果集是否为空对象，如果是默认转化为空数组
     *
     * @param  mixed  ...$params
     *
     * @return array|\Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function cnpscyDetail(...$params)
    {
        $delete_where = $this->is_delete == 0 ? [$this->delete_field => 0] : [];
        $detail = self::query()->where($delete_where)->where(...$params)->first();
        return empty($detail) ? [] : $detail;
    }

    /**
     * 根据条件更新数据
     *
     * @param $where
     * @param $update
     *
     * @return int
     */
    public function whereUpdate($where, $update) : int
    {
        // 如果传参是字符串或者数字类型，默认为主键查询
        if ( is_string($where) || is_numeric($where) ) {
            $where = [$this->getKeyName() => $where];
        }
        $delete_where = $this->is_delete == 0 ? [$this->delete_field => 0] : [];
        return $this->query()->where($delete_where)->where($where)->update($update);
    }

    public function cnpscyWhere(...$params)
    {
        $delete_where = $this->is_delete == 0 ? [$this->delete_field => 0] : [];
        return self::query()->where($delete_where)->where(...$params);
    }

    public function cnpscyWhereIn(...$params)
    {
        $delete_where = $this->is_delete == 0 ? [$this->delete_field => 0] : [];
        return self::query()->where($delete_where)->whereIn(...$params);
    }

    public function cnpscyWith(...$params)
    {
        $delete_where = $this->is_delete == 0 ? [$this->delete_field => 0] : [];
        return self::query()->where($delete_where)->with(...$params);
    }

    public function cnpscyLock($lock = true)
    {
        return self::query()->lock($lock);
    }

    /**
     * 指定字段递增
     *
     * @param  string  $pk
     * @param  string  $field
     * @param  int     $step
     *
     * @return int
     */
    public function setInc(string $pk, string $field, int $step = 1) : int
    {
        return $this->setIncOrDec(1, $pk, $field, $step);
    }

    /**
     * 指定字段递减
     *
     * @param  string  $pk
     * @param  string  $field
     * @param  int     $step
     *
     * @return int
     */
    public function setDec(string $pk, string $field, int $step = 1) : int
    {
        return $this->setIncOrDec(0, $pk, $field, $step);
    }

    private function setIncOrDec(int $operation_type = 1, string $pk, string $field, int $step = 1)
    {
        $increase_or_decrease = $operation_type == 1 ? '+' : '-';
        return Db::update("UPDATE {$this->getTableName()} set {$field} = {$field} {$increase_or_decrease} {$step} WHERE {$this->getKeyName()} = {$pk}");
    }
}
