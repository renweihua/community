<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DeleteScope implements Scope
{
    protected $is_delete = 1; //是否开启删除（1.开启删除，就是直接删除；）
    protected $delete_field = 'is_delete'; //删除字段

    public function __construct(\App\Models\Model $model)
    {
        $this->is_delete = $model->getIsDelete();
        $this->delete_field = $model->getDeleteField();
    }

    /**
     * 把约束加到 Eloquent 查询构造中
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($this->is_delete == 0) $builder->where($this->delete_field, $this->is_delete);
    }
}
