@extends('bbs::layouts.master')

@section('title', $web_title)

@section('content')
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <!-- 内容开始 -->
            <article id="post-box">
                <div class="panel panel-sort">
                    <header class="panel-header">
                        <div class="post-meta-box">
                            <!--面包屑导航开始-->
                            <ol class="breadcrumb">
                                @include('bbs::layouts.breadcrumb')
                                <li class="active">{{ $article->article_title }}</li>
                            </ol>
                            <!--面包屑导航结束-->
                            <div class="meta-top">
                                <span class="date-top">
                                    <i class="fa fa-clock-o"></i>
                                    <time class="pubdate">{{ date('Y-m-d', $article->created_time) }}</time>
                                </span>
                                <span class="comments-top">
                                    <i class="fa fa-eye"></i>
                                    {{ $article->read_num }}次浏览量
                                </span>
                                <span class="comments-top display-none"><i class="fa fa-comments-o"></i> 7条评论</span>
                                <span class="close-sidebar" title="关闭侧边栏"><a href="javascript:;"><i
                                            class="fa fa-toggle-off"></i></a></span>
                                <span class="show-sidebar" title="显示侧边栏" style="display:none;"><a href="javascript:;"><i
                                            class="fa fa-toggle-on"></i></a></span>
                            </div>
                        </div>
                        <h2 class="post-title"><span class="fa fa-code"></span> {{ $article->article_title }} </h2>
                        <ul id="mobile-tab-menu" class="no-js-hide">
                            <li class="current" data-tab="context">内容</li>
                            <li class="" data-tab="related">相关</li>
                        </ul>
                    </header>

                    <section class="context article-content" id="editormd-content">
                        <!-- 如果存在html标签，那么是老文章，直接渲染 -->
                        @if(strip_tags(htmlspecialchars_decode($article->content->article_content)) != htmlspecialchars_decode($article->content->article_content))
                            {!! htmlspecialchars_decode($article->content->article_content) !!}
                        @else
                            <!-- markdown 第一行总是无法解析，手动拼接换行即可。 -->
                            <textarea class="display-none">
                                @if($article->content) {!! PHP_EOL . $article->content->article_content !!} @endif
                            </textarea>
                        @endif

                        <span class="display-none" style="color:#666666;font-size:14px;">至此，教程完毕，有问题可以<a href="javascript:;" target="_blank">留言</a>！</span>
                    </section>

                    <div class="share_list shareBox">
                        <p>
                            <a class="ja_praise action action-like sharebtn abouts display-none" data-ja_praise="29"><i
                                    class="fa fa-heart-o"></i> 赞 (<span>1002</span>)</a>
                            <a class="ja_praise action action-like sharebtn abouts">
                                <i class="fa fa-eye"></i>
                                浏览量 (<span>{{ $article->read_num }}</span>)
                            </a>
                            @if(!empty($alipay_image_code) || !empty($wechat_image_code) || !empty($qq_image_code))
                            <a href="javascript:;" class="sharebtn pay-author"><i class="fa fa-credit-card"></i> 打赏</a>
                            @endif
                            <a href="javascript:;" class="sharebtn J_showAllShareBtn"><i
                                    class="fa fa-paper-plane-o"></i> 分享</a>
                        </p>
                        <div class="socialBox">
                            <div id="wx-qrcode" class="display-none"></div>
                            <div class="bdsharebuttonbox u-share-container f-usn">
                                <ul class="dsye">
                                    <li class="s-weixin js-share-wx" onclick="shareToWeiXin()"
                                        title="分享到朋友圈"></li>
                                    <li class="s-weibo js-share-wb"
                                        onclick="shareToWeibo('{{ $current_url }}', '{{ $web_title }}')"
                                        title="分享到微博"></li>
                                    <li class="s-qzone js-share-qz"
                                        onclick="shareToQzone('{{ $current_url }}', '{{ $web_title }}', '', '{{ $web_description }}')"
                                        title="分享到QQ空间"></li>
                                    <li class="s-note js-share-note"
                                        onclick="shareToQQ('{{ $current_url }}', '{{ $web_title }}', '', '{{ $web_description }}')"
                                        title="分享给QQ好友"></li>
                                </ul>
                            </div>
                            <div class="panel-reward">
                                <ul class="dsye">
                                    @if(!empty($alipay_image_code))
                                        <li class="alipay">
                                            <a href="{{ $alipay_image_code }}" target="_blank" class="highslide">
                                                <img alt="打赏" src="{{ $alipay_image_code }}" />
                                            </a>
                                            <b>支付宝扫一扫</b>
                                        </li>
                                    @endif
                                    @if(!empty($wechat_image_code))
                                        <li class="weixinpay">
                                            <a href="{{ $wechat_image_code }}" target="_blank" class="highslide">
                                                <img alt="打赏" src="{{ $wechat_image_code }}" />
                                            </a>
                                            <b>微信扫一扫</b>
                                        </li>
                                    @endif
                                    @if(!empty($qq_image_code))
                                        <li class="txpay">
                                            <a href="{{ $qq_image_code }}" target="_blank" class="highslide">
                                                <img alt="打赏" src="{{ $qq_image_code }}" />
                                            </a>
                                            <b>企鹅扫一扫</b>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <!-- 内容结束 -->
            <!-- 版权开始 -->
            <div class="post-copyright panel panel-sort sbclass aos-init" data-aos='fade-up'>
                @if(count($article->labels))
                <p class="sidetags">
                    <i class="fa fa-tags"></i> 本文标签：
                    @foreach($article->labels as $label)
                    <a href="javascript:;">{{ $label->label_name }}</a>
                    @endforeach
                </p>
                @endif
                <p>
                    <i class="fa fa-bullhorn"></i>
                    版权声明：若无特殊注明，本文皆为《<a href="javascript:;" title="{{ cnpscy_config('site_web_author') }}">{{ cnpscy_config('site_web_author') }}</a>》原创，转载请保留文章出处。
                </p>
                <p>
                    <i class="fa fa-share-alt-square"></i> 本文链接：{{ $article->article_title }} - {{ $current_url }}
                </p>
            </div>
            <!-- 版权结束 -->
            <!-- 相关开始 -->
            @if(!empty($relation_articles))
            <div class="span12 related-posts-box mobile-hide aos-init" data-aos='fade-up'>
                <div class="panel log_list panel-sort">
                    <header class="panel-header">
                        <h3 class="log_h3">
                            <span class="fa fa-clipboard"></span> 相关文章
                        </h3>
                    </header>
                    <ul class="related-posts row">
                        @foreach($relation_articles as $relation_article)
                            <li class="col-sm-4">
                                <div class="panel transparent related-posts-panel">
                                    <a href="{{ url('detail/' . $relation_article->article_id) }}" class="thumbnail-link" rel="bookmark"
                                       title="{{ $relation_article->article_title }}">
                                        <img src="{{ $relation_article->article_cover }}"
                                             class="thumbnailimg" width="175" height="80" title="{{ $relation_article->article_title }}"
                                             alt="{{ $relation_article->article_title }}"
                                             onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"
                                        />
                                        <div class="excerpt">
                                            {{ $relation_article->article_description }}
                                        </div>
                                    </a>
                                    <div class="bottom-box">
                                        <h4 class="post-title">
                                            <a href="{{ url('detail/' . $relation_article->article_id) }}" title="{{ $relation_article->article_title }}" rel="bookmark">{{ $relation_article->article_title }}</a>
                                        </h4>
                                        <ul class="post-meta">
                                            <li class="author">
                                                <span class="fa fa-github"></span>
                                                <a href="javascript:;" title="{{ cnpscy_config('site_web_author') }}">{{ cnpscy_config('site_web_author') }}</a>
                                            </li>
                                            <li class="date date-abb">
                                                <span class="fa fa-clock-o"></span>
                                                <a href="javascript:;" title="发布于{{ date('Y-m-d', $relation_article->created_time) }}">
                                                    <time>{{ date('Y-m-d', $relation_article->created_time) }}</time>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <!-- 相关结束 -->
            <!-- 评论开始 -->
            <!-- 评论结束 -->
        </div>
    </div>
    <aside class="sidebar">
        <ul class="row">
            <li class="widget widget_ui_sort aos-init display-none" data-aos='fade-up'>
                <div class="widget-title"><span class="icon"><i class="fa fa-file-text-o"></i></span>
                    <h3>分类目录</h3>
                </div>
                <ul class="row sort-ul">
                    <li class="col-sm-6 sort-li"><a title="0 篇文章" href="sort/recommend.htm"><i
                                class="fa fa-twitch"></i> 精选推荐</a></li>
                    <li class="col-sm-6 sort-li"><a title="0 篇文章" href="sort/theme.htm"><i
                                class="fa fa-desktop"></i> 模板插件</a></li>
                    <li class="col-sm-6 sort-li"><a title="0 篇文章" href="sort/vip.htm"><i class="fa fa-credit-card"></i>
                            VIP专区</a></li>
                    <li class="col-sm-6 sort-li"><a title="5 篇文章" href="sort/37.htm"><i class="fa fa-gavel"></i>
                            骗子曝光</a></li>
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

<script src="{{ asset('statics/bbs/qrcode.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
<script>
    $(function () {
        var qrcode = new QRCode('wx-qrcode', {
            text: '{{ $current_url }}',
            width: 256,
            height: 256,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endsection
