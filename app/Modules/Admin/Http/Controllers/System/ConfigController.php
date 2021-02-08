<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\ConfigRequest;
use App\Modules\Admin\Services\ConfigService;

class ConfigController extends BaseController
{
    public function __construct(ConfigService $configService)
    {
        $this->service = $configService;
    }

    public function create(ConfigRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ConfigRequest $request)
    {
        return $this->updateService($request);
    }

    public function getConfigGroupType()
    {
        $config_type_list = $config_group_list = [];
        $config_group = cnpscy_config('config_group_list');
        foreach ($config_group as $key => $value){
            $config_group_list[] = [
                'value' =>  $key,
                'name' =>  $value,
            ];
        }
        $config_type = cnpscy_config('config_type_list');
        foreach ($config_type as $key => $value){
            $config_type_list[] = [
                'value' =>  $key,
                'name' =>  $value,
            ];
        }
        return $this->successJson([
            'config_group_list' => $config_group_list,
            'config_type_list' => $config_type_list,
        ]);
    }

    /**
     * 同步配置
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushRefreshConfig()
    {
        $this->service->pushRefreshConfig();
        return $this->successJson([], '配置文件已同步成功！');
    }
}
