<?php

declare(strict_types=1);

namespace App\Controller\Bbs;

use App\Model\Dynamic\Dynamic;
use App\Model\System\Banner;
use App\Model\System\StartDiagram;
use App\Model\System\Version;
use Psr\Http\Message\ResponseInterface;

class WebSitesController extends BaseController
{
    /**
     * 首页启动图
     *
     * @param  \App\Model\System\StartDiagram  $startDiagrams
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getStartDiagrams(StartDiagram $startDiagrams) : ResponseInterface
    {
        $lists = $startDiagrams->select(['diagram_name', 'diagram_cover'])->orderBy('diagram_sort', 'ASC')->get();
        return $this->successJson($lists);
    }

    /**
     * 注册协议
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function registerAgreement() : ResponseInterface
    {
        return $this->successJson(cnpscy_config('register_agreement'));
    }

    /**
     * 关于我们
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function aboutUs() : ResponseInterface
    {
        return $this->successJson(cnpscy_config('about_us'));
    }

    /**
     * Banner图
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function banners(): ResponseInterface
    {
        $lists = Banner::getBannersByWeb();
        return $this->successJson($lists);
    }

    /**
     * 预览markdown的语法
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function previewMarkdown()
    {
        return $this->successJson(Dynamic::toHTML($this->request->query('markdown')));
    }

    /**
     * 检测APP版本是否升级
     *
     * @param  string  $version_number
     * @param  int     $version_type
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkAppVersion(int $version_type = 1): ResponseInterface
    {
        $request = $this->request;
        $version = Version::select(['version_number', 'apk_url', 'update_type', 'is_update', 'version_desc', 'version_name'])
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
            return $this->errorJson('暂无新版本！');
        }
        return $this->successJson([
            'versionCode' => $version->version_number,
            'versionName' => $version->version_number,
            'versionInfo' => $version->version_desc,
            'updateType' => 'solicit', // $version->is_update == 1 ? 'forcibly' : 'solicit',
            'downloadUrl' => $version->apk_url,
            'update_type' => $version->update_type,
        ]);
    }
}
