<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE')}}">
    <head>
        <meta charset="UTF-8" />
        <meta name="generator" content="{{ cnpscy_config('site_web_author') }}"/>
        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <meta name="author" content="{{ cnpscy_config('site_web_author') }}" />
        <title>@yield('title', $web_title ?? '首页') - {{ cnpscy_config('site_web_title') }}</title>
        <meta name="keywords" content="{{ $web_keywords ?? cnpscy_config('site_web_keywords') }}" />
        <meta name="description" content="{{ $web_description ?? cnpscy_config('site_web_description') }}" />
        <meta name="apple-mobile-web-app-title" content="{{ cnpscy_config('site_web_title') }}"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <link rel="wlwmanifest" type="application/wlwmanifest+xml"/>
        <!-- 移动端与PC站 -->
        <link rel="alternate" type="application/rss+xml" title="RSS"/>

        <link rel="stylesheet" href="{{ asset('statics/bbs/css/style.css?v=' . cnpscy_config('resource_version_number')) }}" type='text/css' media='screen'/>
        <link rel="stylesheet" href="{{ asset('statics/bbs/common.css?v=' . cnpscy_config('resource_version_number')) }}" type='text/css' />
        <script src="{{ asset('statics/bbs/js/jquery.min.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
        <script src="{{ asset('statics/bbs/js/jquery.pjax.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
        <script src="{{ asset('statics/bbs/js/bootstrap.min.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
        <script src="{{ asset('statics/bbs/js/tinymce/tinymce.min.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('statics/bbs/plugins/lanye_zclip/jquery.zclip.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>

        <!-- 百度统计 开始 -->
        <script>
            {!! cnpscy_config('baidu_statistics') !!}
        </script>
        <!-- 百度统计 结束 -->
        <script type="text/javascript">
            var blog_url = "{{ env('APP_URL') }}";
            var pjaxtheme = "{{ asset('statics/bbs/') }}/";
            var pjax_id = '#pjax-body';
        </script>
        @yield('style-src')
        @yield('style')
    </head>
    <body class="nav-fixed" id="pjax-body" data-aos-easing="ease-in-sine" data-aos-duration="500" data-aos-delay="100">

        @include('bbs::layouts.navbar')

        <div id="pjax-content">
            @yield('content')

            @include('bbs::layouts.mobile-footer')
        </div>

        @include('bbs::layouts.footer')

        <div class="loading">
            <div class="loading1">
                <div class="block"></div>
                <div class="block"></div>
                <div class="block"></div>
                <div class="block"></div>
                <div class='section-left'></div>
                <div class='section-right'></div>
            </div>
        </div>

        @include('bbs::layouts.rollbar-top')

        <script type="text/javascript" src="{{ asset('statics/bbs/js/sweetalert.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
        <script type="text/javascript" src="{{ asset('statics/bbs/js/masonry.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
        <script type="text/javascript" src="{{ asset('statics/bbs/js/main.js?v=' . cnpscy_config('resource_version_number')) }}"></script>

        <!-- AOS：页面滚动动画 开始 -->
        <link rel="stylesheet" type="text/css" href="{{ asset('statics/aos@3.0.0-beta.6/aos.css?v=' . cnpscy_config('resource_version_number')) }}" />
        <script type="text/javascript" src="{{ asset('statics/aos@3.0.0-beta.6/aos.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
        <script type="text/javascript">
            AOS.init({
                offset: 200,
                duration: 500,
                easing: 'ease-in-sine',
                delay: 100,
                once: true,
            });
        </script>
        <!-- AOS：页面滚动动画 结束 -->

        <!-- lazyload 图片延迟加载 -->
        <script src="{{ asset('statics/bbs/js/jquery.lazyload.js?v=' . cnpscy_config('resource_version_number')) }}" type="text/javascript"></script>
        <script>
            $(function() {
                $("img.lazyload").lazyload({effect: "fadeIn"});
            });
        </script>

        @yield('script-src')

        @yield('script')
    </body>
</html>
