@extends('bbs::layouts.master')

@section('content')
    <section class="container">
        <div class="content-wrap">
            <div id="content" class="content container-tw">
                <header class="article-header">
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
                <section class="context" id="editormd-content">
                    <!-- 如果存在html标签，那么是老文章，直接渲染 -->
                    @if($menu->menu_content && strip_tags($menu->menu_content) != $menu->menu_content)
                        {!! $menu->menu_content !!}
                    @else
                        <textarea class="display-none">
                            @if($menu->menu_content) {!! $menu->menu_content !!} @endif
                        </textarea>
                    @endif
                </section>
            </div>
        </div>
        <aside class="sidebar">
            <ul class="row">
                <li class="widget widget_ui_sort">
                    <div class="widget-title"><span class="icon"><i class="fa fa-file-text-o"></i></span>
                        <h3>分类目录</h3>
                    </div>
                    <ul class="row sort-ul">
                        <li class="col-sm-6 sort-li">
                            <a title="0 篇文章" href="sort/recommend.htm">
                                <i class="fa fa-twitch"></i> 精选推荐
                            </a>
                        </li>
                        <li class="col-sm-6 sort-li">
                            <a title="0 篇文章" href="sort/theme.htm">
                                <i class="fa fa-desktop"></i> 模板插件
                            </a>
                        </li>
                        <li class="col-sm-6 sort-li">
                            <a title="0 篇文章" href="sort/vip.htm">
                                <i class="fa fa-credit-card"></i> VIP专区
                            </a>
                        </li>
                        <li class="col-sm-6 sort-li">
                            <a title="5 篇文章" href="sort/37.htm">
                                <i class="fa fa-gavel"></i>
                                骗子曝光
                            </a>
                        </li>
                    </ul>
                </li>

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

@section('script')
    @include('bbs::layouts.markdown')
@endsection
