@extends('bbs::layouts.master')

@section('content')
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <!--轮播图开始-->
            <!--  data-ride="carousel" 自动轮播 -->
            @if(!empty($banners))
            <div id="wowslider-container1">
                <div class="ws_images">
                    <ul>
                        @foreach ($banners as $banner)
                        <li>
                            <a href="{{ $banner->banner_link ? $banner->banner_link : 'javascript:;' }}"
                               title="{{ $banner->banner_title }}" target="_blank">
                                <img src="{{ $banner->banner_cover }}" title="{{ $banner->banner_title }}"
                                     alt="{{ $banner->banner_title }}"/>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="ws_shadow"></div>
            </div>
            <div class="clearfloat"></div>
            @endif
            <!--轮播图结束-->

            <!-- 公告 -->
            <article class="excerpt-minic excerpt-minic-index aos-init" data-aos='fade-up'>
                <div class="textgg">
                    <ul class="gglb">
                        <i class="fa fa-bullhorn"></i>
                        <div class="bulletin">
                            <ul>
                                <li>
                                    <a href="javascript:;">这里是公告消息1（xxxx年xx月xx日）</a>
                                </li>
                                <li>
                                    <a href="javascript:;">这里是公告消息2（xxxx年xx月xx日）</a>
                                </li>
                                <li>
                                    <a href="javascript:;">这里是公告消息3（xxxx年xx月xx日）</a>
                                </li>
                            </ul>
                        </div>
                    </ul>
                </div>
            </article>
            <!-- 首页6格 -->

            @include('bbs::layouts.excerpt-list')

            <!--
            <article class="excerpt-list excerp-user aos-init" data-aos='fade-up'>
                <div class="fly-user-title cl">
                    <h2>活跃用户</h2>
                    <span id="chakhsu"></span> <a href="javascript:;" title="此处按VIP等级排行">排行</a>
                </div>
                <div class="area">
                    <div class="frame cl">
                        <div class="block">
                            <div class="fly_user">
                                <ul class="fly_user-list row">
                                    <li class="fly_user-li col-sm-2 col-xs-4" user-id="1">
                                        <a href="javascript:;" class="avatar" alt="{{ cnpscy_config('site_web_author') }}">
                                            <i title="本站管理员" class="user-identw"></i>
                                            <img class="lazyload"
                                               src="statics/bbs/img/avatar.png"
                                               alt="{{ cnpscy_config('site_web_author') }}" />
                                        </a>
                                    </li>
                                    <li class="fly_user-li col-sm-2 col-xs-4" user-id="2">
                                        <a href="javascript:;" class="avatar" alt="鬼少">
                                            <i title="钻石会员" class="user-vip-level3"></i>
                                            <img class="lazyload"
                                                  src="statics/bbs/img/avatar.png"
                                                  alt="鬼少" />
                                        </a>
                                    </li>
                                    <li class="fly_user-li col-sm-2 col-xs-4" user-id="235">
                                        <a href="javascript:;" class="avatar" alt="未命名">
                                            <i title="普通会员" class="user-ident"></i>
                                            <img class="lazyload"
                                                 src="statics/bbs/img/avatar.png"
                                                 alt="" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            -->

            <article class="focusmo display-none aos-init" data-aos='fade-up'>
                <ul>
                    <li class="large">
                        <a href="javascript:;">
                            <img src="statics/bbs/img/lazyload.gif" class="thumb lazyload" />
                            <h4>这里是公告消息</h4>
                        </a>
                    </li>
                </ul>
            </article>

            <!-- 广告位置：首页_列表_横幅_h760w80 | ID:1 -->
            <article class="excerpt-list min-height-100-px text-center line-height-100-px aos-init" data-aos='fade-up'>
                广告位置：还没想好放什么……
            </article>
            <!-- 广告结束 投放请联系站长 -->

            <!-- 列表 -->
            @if(!empty($articles))
                @foreach($articles as $article)
                    @include('bbs::render.article')
                @endforeach
                <!--分页开始-->
                @if($articles->lastPage() > 1)
                    <article class="excerpt excerpt-page aos-init" data-aos='fade-up'>
                        <div class="pagination">
                            {{ $articles->render('bbs::layouts.paginator') }}
                        </div>
                    </article>
                @endif
                <!--分页结束-->
            @endif

            <article class="excerpt-list min-height-100-px text-center line-height-100-px aos-init" data-aos='fade-up'>
                广告位置：还没想好放什么……
            </article>

            <!-- cms开始 -->
            @include('bbs::layouts.cms')
            <!-- cms结束 -->

            <!--分页开始-->
        </div>
    </div>
    <aside class="sidebar">
        <ul class="row">
            <li class="widget widget_ui_blogger aos-init" data-aos='fade-up'>
                <article class="panel-side">
                    <div class="fly_weibo">
                        <ul class="blogger_side">
                            <div id="weiboShow">
                                <div class="grid-weibo-show shadow-hover">
                                    <header id="shead">&nbsp;</header>
                                    <div id="user-login" class="contentt">
                                        <div class="avatar">
                                            <img src="{{ cnpscy_config('site_web_logo') }}" />
                                            <i title="{{ cnpscy_config('site_web_author') }}" class="author-ident"></i>
                                            <div class="overlay">
                                                <a href="#" class="expand" data-target="#myLogin"
                                                   data-toggle="modal" data-backdrop="static"
                                                   target="_blank">Login</a>
                                            </div>
                                            <span class="rank"></span>
                                        </div>
                                        <h4>{{ cnpscy_config('site_web_author') }}</h4>
                                        <p class="seta">{{ cnpscy_config('site_author_slogan') }}</p>
                                        <div class="author-social">
                                            <span class="author-blog">
                                                <a
                                                    href="javascript:if(confirm('http://wpa.qq.com/msgrd?v=3&uin=2278757482&site=qq&menu=yes  \n\n���ļ��޷��� Teleport Ultra ����, ��Ϊ ����һ�����·���ⲿ������Ϊ������ʼ��ַ�ĵ�ַ��  \n\n�����ڷ������ϴ���?'))window.location='http://wpa.qq.com/msgrd?v=3&uin=2278757482&site=qq&menu=yes'"
                                                    rel="nofollow" target="_blank">
                                                    <i class="fa fa-qq"></i>博主
                                                </a>
                                            </span>
                                            <span class="author-weibo  display-none">
                                                <a href="javascript:;"
                                                    rel="nofollow" target="_blank">
                                                        <i class="fa fa-thumbs-up"></i>
                                                        店铺
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    <footer>
                                        <ul class="blogger_footer">
                                            <li><strong>{{$articles_count}}</strong><span>文章</span></li>
                                            <!--
                                            <li><strong>6252</strong><span>评论</span></li>
                                            <li><strong>33</strong><span>微语</span></li>
                                            -->
                                        </ul>
                                    </footer>
                                </div>
                            </div>
                        </ul>
                    </div>
                </article>
            </li>
            <li class="widget widget_calendar display-none">
                @include('bbs::layouts.calendar')
            </li>
            <li class="widget widget_text display-none">
                <div class="widget-title"><span class="icon"><i class="fa fa-th-large"></i></span>
                    <h3>小丑路人</h3>
                </div>
                <div class="widget-zdy">
                </div>
            </li>

            <!-- 随机文章 -->
            @include('bbs::layouts.rand-articles')

            <!-- 最新文章 -->
            @include('bbs::layouts.new-articles')

            <li class="widget widget_ui_statistics display-none aos-init" data-aos='fade-up'>
                <div class="widget-title">
                    <span class="icon"><i class="fa fa-signal"></i></span>
                    <h3>网站统计</h3>
                </div>
                <ul>
                    <li>用户总数：859位</li>
                    <li>置顶文章：1篇</li>
                    <li>微语总数：33条</li>
                    <li>评论总数：6252条</li>
                    <li>标签总数：278条</li>
                    <li>友链总数：55条</li>
                    <li>运行天数：2086天</li>
                    <li>最近更新：9月8日</li>
                </ul>
            </li>
        </ul>
    </aside>
</section>
<div class="container footer_">
    @if(!empty($friendlinks))
    <div class="links_ mbl">
        <div class="links_bt">
            <div class="links_bt_l">
                <a href="javascript:;">友情链接 <small>(排名不分先后)</small></a>
            </div>
            <div class="links_bt_r display-none">
                <a href="javascript:;">更多</a>
            </div>
        </div>
        <div class="links_lb">
            <ul>
                @foreach($friendlinks as $friendlink)
                <li>
                    <a href="{{ $friendlink->link_url }}" title="{{ $friendlink->link_name }}" target="_blank">{{ $friendlink->link_name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script-src')
    <script type="javascript" src="{{ asset('statics/bbs/js/slider.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
@endsection
