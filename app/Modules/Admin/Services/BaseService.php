<?php

namespace App\Modules\Admin\Services;

use App\Models\MonthModel;
use App\Services\Service;

class BaseService extends Service
{
    protected $model;
    protected $with = [];
    protected $detail;
    protected $detailWith = [];

    /**
     * 列表分页数据
     *
     * @param  array  $params
     *
     * @return array
     */
    public function lists(array $params): array
    {
        // 如果是按月分表的模型，设置按月份查询的月份表
        if ($this->model instanceof MonthModel){
            $this->model = $this->model->setMonthTable($this->getSearchMonth());
        }
        $model = $this->model->where($params['where_callback'] ?? [])
            ->with($this->with)
            ->orderBy(empty($params['order']) ? $this->model->getKeyName() : $params['order'], empty($params['order_sort']) ? 'DESC' : $params['order_sort']);
        // 如果是下载，那么数据将不分页。
        if (isset($params['is_download']) && $params['is_download'] == 1){
            return $model->get()->toArray() ?? [];
        }else{
            $lists = $model->paginate($this->getLimit($params['limit'] ?? 10));
            return [
                'current_page' => $lists->currentPage(),
                'per_page' => $lists->perPage(),
                'count_page' => $lists->lastPage(),
                'total' => $lists->total(),
                'data' => $lists->items(),
            ];
        }
    }

    /**
     * 详情
     *
     * @param $id
     *
     * @return mixed
     */
    public function detail($request)
    {
        $id = $request->input($this->model->getKeyName(), 0);
        return $this->model->detail($id, '*', false, $this->detailWith);
    }

    /**
     * 新增数据
     *
     * @param  array  $params
     *
     * @return mixed
     */
    public function create(array $params)
    {
        return $this->detail = $this->model->create($this->model->setFilterFields($params));
    }

    /**
     * 更新数据
     *
     * @param  array  $params
     *
     * @return mixed
     */
    public function update(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        $this->detail = $this->model->find($params[$primaryKey]);
        foreach ($this->model->setFilterFields($params) as $field => $value){
            $this->detail->$field = $value ?? '';
        }
        return $this->detail->save();
    }

    /**
     * 删除：单个或匹配删除
     * @param  array  $params
     *
     * @return bool
     */
    public function delete(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        if ( empty($params[$primaryKey]) && empty($params['is_batch'])) {
            $this->setError('请指定删除标识！');
            return false;
        }
        // 是否为批量删除
        if (isset($params['is_batch']) && $params['is_batch'] == 1){
            $ids = explode(',', $params['ids'] ?? $params[$primaryKey]);
        }else{
            $ids = [$params[$primaryKey]];
        }
        /**
         * 如果是月份表进行删除的话，那么需要指定表名
         */
        if ($this->model instanceof MonthModel && isset($params['month'])){
            $this->model = $this->model->setMonthTable($params['month']);
        }
        if ($this->model->getIsDelete() == 0){
            return $this->model->whereIn($primaryKey, $ids)->update([$this->model->getDeleteField() => 1]);
        }else{
            return $this->model->whereIn($primaryKey, $ids)->delete();
        }
    }

    /**
     * 指定字段变动
     * @param  array  $params
     *
     * @return bool
     */
    public function changeFiledStatus(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        if (empty($params[$primaryKey])) {
            $this->setError('请指定删除标识！');
            return false;
        }
        if ( $this->model->where([$primaryKey => $params[$primaryKey]])->update([$params['change_field'] => $params['change_value']]) ) {
            $this->setError('设置成功！');
            return true;
        } else {
            $this->setError('设置失败！');
            return false;
        }
    }

    /**
     * 下拉列表
     *
     * @param $request
     *
     * @return mixed
     */
    public function getSelectLists($request)
    {
        return $this->model->orderBy($this->model->getKeyName(), 'ASC')->limit(100)->get();
    }
}
