<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\Dynamic\Topic;
use App\Models\System\StartDiagram;
use Illuminate\Http\JsonResponse;

class WebSitesController extends BbsController
{
    /**
     * 首页启动图
     *
     * @param  \App\Models\System\StartDiagram  $startDiagrams
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStartDiagrams(StartDiagram $startDiagrams):JsonResponse
    {
        $lists = $startDiagrams->select(['diagram_name', 'diagram_cover'])->orderBy('diagram_sort', 'ASC')->get();
        return $this->successJson($lists);
    }

    /**
     * 荟吧列表
     *
     * @param  \App\Models\Dynamic\Topic  $topic
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topics(Topic $topic)
    {
        $lists = $topic->orderBy('topic_sort', 'ASC')->get();
        return $this->successJson($lists);
    }

    /**
     * 注册协议
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAgreement():JsonResponse
    {
        return $this->successJson(cnpscy_config('register_agreement'));
    }

    /**
     * 关于我们
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutUs(): JsonResponse
    {
        return $this->successJson(cnpscy_config('about_us'));
    }
}
