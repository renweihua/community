@extends('bbs::layouts.master')

@section('content')
    <section class="container">
        <div class="content-wrap">
            <div class="content min-height-auto aos-init" data-aos="fade-up">
                <!-- 广告位置：首页_列表_横幅_h760w80 | ID:1 -->
                <article class="excerpt-list min-height-100-px text-center line-height-100-px">
                    广告位置：还没想好放什么……
                </article>
            </div>
            <div id="content" class="content container-tw aos-init" data-aos="fade-up">
                <header class="article-header">
                    <h1 class="title-tw">
                        <!--面包屑导航开始-->
                        <ol class="breadcrumb">
                            @include('bbs::layouts.breadcrumb')
                            <li class="active">
                                <i class="fa fa-list"></i> 文章归档 <span class="toggler">折叠归档</span>
                            </li>
                        </ol>
                        <!--面包屑导航结束-->
                    </h1>
                </header>
                <section class="context">
                    <div class="archives">
                        @if(!empty($lists))
                            @foreach($lists as $key => $articles)
                                <h4>
                                    {{ $key }}
                                    <span> {{ count($articles) }}篇 </span>
                                </h4>
                                @if(!empty($articles))
                                    <ul>
                                        @foreach($articles as $article)
                                            <li class="goodwork">
                                                <a title="{{ $article['article_title'] }}" href="{{ url('/detail/' . $article['article_id']) }}">
                                                    <span>{{ date('m月d日', $article['created_time']) }}</span>
                                                    <div class="atitle">{{ $article['article_title'] }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
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
