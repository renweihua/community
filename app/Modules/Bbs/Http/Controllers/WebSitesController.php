<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\System\StartDiagram;

class WebSitesController extends BbsController
{
    /**
     * 首页启动图
     *
     * @param  \App\Models\System\StartDiagram  $startDiagrams
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStartDiagrams(StartDiagram $startDiagrams)
    {
        $list = $startDiagrams->select(['diagram_name', 'diagram_cover'])->orderBy('diagram_sort', 'ASC')->get();
        return $this->successJson($list);
    }
}
