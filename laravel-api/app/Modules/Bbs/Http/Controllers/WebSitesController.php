<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\Dynamic\Dynamic;
use App\Models\System\Banner;
use App\Models\System\StartDiagram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebSitesController extends BbsController
{
    /**
     * 首页启动图
     *
     * @param  \App\Models\System\StartDiagram  $startDiagrams
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStartDiagrams(StartDiagram $startDiagrams) : JsonResponse
    {
        $lists = $startDiagrams->select(['diagram_name', 'diagram_cover'])->orderBy('diagram_sort', 'ASC')->get();
        return $this->successJson($lists);
    }

    /**
     * 注册协议
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAgreement() : JsonResponse
    {
        return $this->successJson(cnpscy_config('register_agreement'));
    }

    /**
     * 关于我们
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutUs() : JsonResponse
    {
        return $this->successJson(cnpscy_config('about_us'));
    }

    /**
     * Banner图
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function banners(): JsonResponse
    {
        $lists = Banner::getBannersByWeb();
        return $this->successJson($lists);
    }

    /**
     * 预览markdown的语法
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string|string[]|null
     */
    public function previewMarkdown(Request $request)
    {
        return $this->successJson(Dynamic::toHTML($request->get('markdown')));
    }
}
