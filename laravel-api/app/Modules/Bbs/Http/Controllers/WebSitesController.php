<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\Dynamic\Dynamic;
use App\Models\System\Banner;
use App\Models\System\Friendlink;
use App\Models\System\StartDiagram;
use App\Models\AppVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebSitesController extends BbsController
{
    /**
     * 首页启动图
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStartDiagrams() : JsonResponse
    {
        $lists = StartDiagram::getStartDiagrams();
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
     * 友情链接
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendlinks(): JsonResponse
    {
        $lists = Friendlink::getFriendlinksByWeb();
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

    /**
     * 检测APP版本是否升级
     *
     * @param  string  $version_number
     * @param  int     $version_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAppVersion(Request $request, $version_type = 1): JsonResponse
    {
        $version = AppVersion::select(['version_number', 'apk_url', 'update_type', 'is_update', 'version_desc', 'version_name'])
            ->where(function($query) use($request){
                if ($request->version_number){
                    $query->where('version_number', '>', $request->version_number);
                }
            })
            ->where('version_type', '=', $request->version_type ?? $version_type)
            ->orderByDesc('version_number')
            ->first();

        /* res的数据说明
          * | 参数名称        | 一定返回     | 类型        | 描述
          * | -------------|--------- | --------- | ------------- |
          * | versionCode     | y        | int       | 版本号        |
          * | versionName     | y        | String    | 版本名称      |
          * | versionInfo     | y        | String    | 版本信息      |
          * | updateType      | y        | String    | forcibly = 强制更新, solicit = 弹窗确认更新, silent = 静默更新 |
          * | downloadUrl     | y        | String    | 版本下载链接（IOS安装包更新请放跳转store应用商店链接,安卓apk和wgt文件放文件下载链接）  |
          */
        if (empty($version)){
            return $this->successJson([], '已是最新版本！');
        }
        return $this->successJson([
            'versionCode' => $version->version_code,
            'versionName' => $version->version_number,
            'versionInfo' => $version->version_desc,
            'updateType' => 'solicit', // $version->is_update == 1 ? 'forcibly' : 'solicit',
            'downloadUrl' => $version->apk_url,
            'update_type' => $version->update_type,
        ]);
    }
}
