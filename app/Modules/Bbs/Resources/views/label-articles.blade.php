@extends('bbs::layouts.master')

@section('content')
	<section class="container">
        <div class="content-wrap">
            <div class="content">

                <!-- 广告位置：首页_列表_横幅_h760w80 | ID:1 -->
                <article class="excerpt-list min-height-100-px text-center line-height-100-px aos-init" data-aos="fade-up">
                    广告位置：还没想好放什么……
                </article>
                <!-- 广告结束 投放请联系站长 -->

                <!-- 列表 -->
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
