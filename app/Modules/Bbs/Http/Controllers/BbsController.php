<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Article\Article;
use App\Models\Article\ArticleLabel;
use App\Models\Bbs\Menu;
use App\Models\System\Banner;
use App\Models\System\Friendlink;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class BbsController extends Controller
{
    protected $article_instance;

    public function __construct()
    {
        $menus = Menu::getMenusByWeb();

        $this->article_instance = Article::getInstance();
        // 随机文章
        $rand_articles = $this->article_instance->check()->where('article_images', '<>', '')->orderByRaw("RAND()")->limit(6)->get();
        // 最新文章
        $new_articles = $this->article_instance->check()->orderBy('created_time', 'DESC')->limit(5)->get();

        view()->share([
            'menus' => $menus,
            'rand_articles' => $rand_articles,
            'new_articles' => $new_articles,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // Banner
        $banners = Banner::getBannersByWeb();

        // 文章总量
        $articles_count = $this->article_instance->cacheStatistics();

        // 文章分页
        $articles = $this->getArticles($this->article_instance);

        // 友情链接
        $friendlinks = Friendlink::getFriendlinksByWeb();

        return view('bbs::index', compact('articles_count', 'banners', 'articles', 'friendlinks'));
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     *
     * @return Renderable
     */
    public function label($label_id)
    {
        $lists = ArticleLabel::getInstance()->detail($label_id)->articles()->check()->with([
            'labels',
            'menu',
        ])->where('is_public', 1)->paginate(15);
        return view('bbs::label-articles', compact('lists'));
    }

    /**
     *
     * @param  int  $article_id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function detail(int $article_id)
    {
        $article_instance = Article::check();
        $article = $article_instance->select('article_id', 'menu_id', 'article_title', 'article_keywords', 'article_description', 'read_num', 'created_time')->where('article_id', $article_id)->with([
            'labels',
            'content',
            'menu',
        ])->first();
        if ( !$article ) {
            throw new InvalidRequestException('NOT FOUND ARTICLE！', 404);
        }

        // 阅读量自增
        $article->increment('read_num');

        $web_title = $article->article_title;
        $web_keywords = $article->article_keywords;
        $web_description = $article->article_description;

        // 当前URL
        $current_url = url()->current();

        // 随机获取三条相关文章
        $relation_articles = $article_instance->select('article_id', 'article_title', 'article_description', 'article_images', 'created_time')->whereIn('menu_id', Menu::getMenusChilds($article->menu_id))->orderByRaw("RAND()")->limit(3)->get();

        // 获取当前位置
        $location_menus = Menu::getInstance()->getLocation((int)$article->menu_id);

        // 标签列表
        $labels = ArticleLabel::getLabelsByCache();

        // 打赏
        $alipay_image_code = cnpscy_config('alipay_image_code');
        $wechat_image_code = cnpscy_config('wechat_image_code');
        $qq_image_code = cnpscy_config('qq_image_code');

        return view('bbs::detail', compact('article', 'web_title', 'web_keywords', 'web_description', 'current_url', 'relation_articles', 'location_menus', 'labels', 'alipay_image_code', 'wechat_image_code', 'qq_image_code'));
    }

    /**
     * 前端的自定义菜单路由
     *
     * @param $menu_url
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function menuUrl($menu_url)
    {
        $menu = Menu::getInstance()->getMenuByUrl($menu_url);
        if ( !$menu ) {
            throw new InvalidRequestException('NOT FOUND！', 404);
        }
        $lists = [];
        if ( $menu->menu_tpltype == 0 ) { // 作为频道页，不可作为栏目发布文章
            switch ($menu->menu_url){
                case 'time-axis': // 文章归档
                    $articles = $this->getArticles($this->article_instance, [], false)->toArray();
                    $lists = [];
                    foreach ($articles as $article){
                        $lists[date('Y.m', $article['created_time'])][] = $article;
                    }
                    break;
                case 'friendlinks': // 友情链接
                    View::share('friendlinks', Friendlink::getFriendlinksByWeb());
                    break;
            }
        } elseif ( $menu->menu_tpltype == 1 ) { // 不直接发布内容，用于跳转页面
            return redirect('Location: ' . $menu->menu_url);
        } elseif ( $menu->menu_tpltype == 2 ) { // 文章列表页
            $lists = $this->getArticles($this->article_instance, $menu);
        } elseif ( $menu->menu_tpltype == 3 ) { // 单页面模式

        }

        // Title 标题
        $web_title = $menu->menu_name;

        // 获取当前位置
        $location_menus = Menu::getInstance()->getLocation((int)$menu->menu_id, false);

        return view('bbs::' . $menu->menu_listtpl, compact('lists', 'menu', 'web_title'))->with('location_menus', $location_menus) //当前页面的位置
            ->with('web_keywords', empty($menu->menu_keywords) ? cnpscy_config('site_web_keywords') : $menu->menu_keywords)//Head的关键字
            ->with('web_description', empty($menu->menu_description) ? cnpscy_config('site_web_description') : $menu->menu_description); //Head的描述
    }

    private function getArticles($article, $menu = [], bool $is_paginate = true)
    {
        $article = $article->check()->where('is_public', '<>', 0); //0：秘密；1.公开；2.密码访问
        if ($menu){
            $article = $article->whereIn('menu_id', Menu::getMenusChilds($menu->menu_id)); //栏目筛选
        }
        // 排序
        $article = $article->orderBy('set_top', 'DESC')->orderBy('is_recommend', 'DESC')->orderBy('article_sort', 'ASC')->orderBy('article_id', 'DESC')->with([
            'labels',
            'menu' => function($query) {
                $query->select([
                    'menu_id',
                    'menu_name',
                    'menu_url',
                ]);
            },
        ])->select([
            'article_id',
            'menu_id',
            'article_title',
            'article_description',
            'article_images',
            'article_link',
            'article_origin',
            'created_time',
            'is_recommend',
            'set_top',
            'read_num',
        ]);
        if ($is_paginate){
            //每页的数量
            return $article->paginate(15);
        }else{
            return $article->get();
        }
    }
}
