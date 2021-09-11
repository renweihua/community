<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Rabc\Admin;
use App\Traits\Json;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use Json;

    protected $guard = 'admin';

    protected $service;

    public function getLoginUser(): Admin
    {
        return Auth::guard($this->guard)->user();
    }

    public function getLoginUserId(): int
    {
        return $this->getLoginUser()->admin_id ?? 0;
    }

    /**
     * 列表页
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        return $this->successJson($this->service->lists($request->all()));
    }

    /**
     * 详情
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($detail = $this->service->detail($request)){
            return $this->successJson($detail);
        }else{
            return $this->errorJson('数据不存在！');
        }
    }

    /**
     * 新增流程
     *
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createService($request): JsonResponse
    {
        if ($request instanceof FormRequest){
            $request->validated();
        }

        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->create($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 更新流程
     *
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateService($request): JsonResponse
    {
        if ($request instanceof FormRequest){
            $request->validated();
        }

        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->update($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 删除
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->delete($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定字段变更操作
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeFiledStatus(Request $request): JsonResponse
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }

        if ($this->service->changeFiledStatus($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 下拉筛选列表（可搜索）
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelectLists(Request $request): JsonResponse
    {
        $lists = $this->service->getSelectLists($request);
        return $this->successJson($lists);
    }
}
