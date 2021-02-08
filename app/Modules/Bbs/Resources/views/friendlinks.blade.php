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
                            <i class="fa fa-link"></i> 友情链接
                        </li>
                    </ol>
                    <!--面包屑导航结束-->
                </h1>
            </header>
            <section class="context">
                <div class="blue" style="list-style-type: none; box-sizing: border-box; font-size: 12px; font-family: 'Lucida Console'; color: #1bb3f0; margin: 0 0 20px; border-radius: 2px; border: #8accff 1px solid; padding: 10px 30px 10px 30px;">
                    <span style="box-sizing: border-box; font-family: 'Microsoft YaHei', 微软雅黑, 'Trebuchet MS', Arial, Verdana, Tahoma, sans-serif; font-size: 14px;">
                        &nbsp;拒绝推广类广告网站，抵制一切非法网站淫秽网站等。
                    </span>
                    <br style="box-sizing: border-box;"/>
                    <span style="font-size: 14px;"> ❤ 请先提前对本站博客做好友情链接</span>
                    <br style="box-sizing: border-box;"/>
                    <span style="font-size: 14px;"> ❤ 交换首页友情链接</span>
                    <br style="box-sizing: border-box;"/>
                    <span style="font-size: 14px;"> ❤ 没有满足以上条件的请勿打扰！</span></div>
                <div class="row blogroll">
                    <div id="links1" class="links-panel">
                        <blockquote><i class="fa fa-link"></i>友情链接</blockquote>
                        @if(!empty($friendlinks))
                            @foreach($friendlinks as $friendlink)
                                <div class="col-md-3 col-sm-4 col-xs-6 linkli">
                                    @if(!empty($friendlink->link_url))
                                        <a href="{{ $friendlink->link_url }}" title="{{ $friendlink->link_name }}" target="_blank">
                                            @if(!empty($friendlink->link_cover))
                                                <img alt="link" src="{{ $friendlink->link_cover }}" onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"/>
                                            @endif
                                            {{ $friendlink->link_name }}
                                        </a>
                                    @else
                                        <a href="javascript:;">{{ $friendlink->link_name }}</a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="clearfix"></div>
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
