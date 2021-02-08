@extends('bbs::layouts.master')

@section('content')
	<section class="container">
        <div class="content-wrap">
            <div class="content list-content">
                <!-- 广告位置：首页_列表_横幅_h760w80 | ID:1 -->
                <article class="excerpt-list min-height-100-px text-center line-height-100-px aos-init" data-aos="fade-up">
                    广告位置：还没想好放什么……
                </article>

                <!-- 广告结束 投放请联系站长 -->
                <header class="article-header aos-init" data-aos="fade-up">
                    <h1 class="title-tw">
                        <!--面包屑导航开始-->
                        <ol class="breadcrumb">
                            @include('bbs::layouts.breadcrumb')
                            <li class="active">
                                @if(!empty($menu->menu_icon))<i class="fa {{ $menu->menu_icon }}"></i>@endif
                                {{ $menu->menu_name }}
                            </li>
                        </ol>
                        <!--面包屑导航结束-->
                    </h1>
                </header>

                <!-- 列表 开始-->
                @if(!empty($lists))
                    @foreach($lists as $article)
                        @include('bbs::render.article')
                    @endforeach

                    <!--分页开始-->
                    @if($lists->lastPage() > 1)
                        <article class="excerpt excerpt-page aos-init" data-aos='fade-up'>
                            <div class="pagination">
                                {{ $lists->render('bbs::layouts.paginator') }}
                            </div>
                        </article>
                    @endif
                    <!--分页结束-->
                @endif
                <!-- 列表 结束-->
            </div>
        </div>
        <aside class="sidebar">
            <ul class="row">
                <!-- 热门标签 -->
                @include('bbs::layouts.hot-labels')

                <!-- 随机文章 -->
                @include('bbs::layouts.rand-articles')

                <!-- 最新文章 -->
                @include('bbs::layouts.new-articles')
            </ul>
        </aside>
    </section>
@endsection
