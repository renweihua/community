<nav id="header" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand logo">
                <img src="{{ cnpscy_config('site_web_logo') }}" alt="{{ cnpscy_config('site_web_author') }}" />
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        @if(!empty($menus))
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav nav-top">
                @foreach($menus as $menu)
                    <li class="
                    @if(!empty($menu['_child']))
                        dropdown
                    @endif">
                        <a href="{{ empty($menu['menu_url']) ? 'javascript:;' : url($menu['menu_url']) }}"
                        @if(!empty($menu['_child']))
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                        @endif
                           >
                            <i class='fa {{ $menu['menu_icon'] }}'></i>
                            {{ $menu['menu_name'] }}

                            @if(!empty($menu['_child']))
                                <span class="fa fa-angle-down"></span>
                            @endif
                        </a>
                        @if(!empty($menu['_child']))
                            <ul class="dropdown-menu">
                                @foreach($menu['_child'] as $child)
                                    <li>
                                        <a href="{{ empty($child['menu_url']) ? 'javascript:;' : url($child['menu_url']) }}">
                                            <i class="fa {{ $child['menu_icon'] }}"></i>
                                            {{ $child['menu_name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <div class="display-none">
                    <li class="active">
                        <a href="javascript:;">
                            <i class='fa fa-home'></i> 首页
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="sort/recommend.htm" class="dropdown-toggle"
                           data-toggle="dropdown">
                            <i class='fa fa-twitch'></i> 精选推荐 <span class="fa fa-angle-down"></span>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="sort/grfx.htm"><i class="fa fa-plug"></i>
                                    个人分享</a></li>
                            <li><a href="sort/sxbj.htm"><i
                                        class="fa fa-pencil-square-o"></i> 随心笔记</a></li>
                            <li><a href="sort/tools.htm"><i class="fa fa-wrench"></i>
                                    常用工具</a></li>
                            <li><a href="sort/system.htm"><i class="fa fa-windows"></i>
                                    纯净系统</a></li>
                            <li><a href="sort/jiqiao.htm"><i class="fa fa-globe"></i>
                                    网络技巧</a></li>
                            <li><a href="sort/jzjc.htm"><i class="fa fa-code"></i>
                                    建站教程</a></li>
                            <li><a href="sort/movie.htm"><i
                                        class="fa fa-play-circle"></i> 影视资源</a></li>
                            <li><a href="sort/contribute.htm"><i
                                        class="fa fa-thumbs-o-up"></i> 网友投稿</a></li>
                            <li><a href="sort/gaoxiao.htm"><i
                                        class="fa fa-picture-o"></i> 搞笑时刻</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="sort/theme.htm" class="dropdown-toggle"
                           data-toggle="dropdown">
                            <i class='fa fa-language'></i> 模板插件 <span class="fa fa-angle-down"></span>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="sort/emlog.htm"><i class="fa fa-desktop"></i>
                                    emlog</a></li>
                            <li><a href="sort/zblog.htm"><i
                                        class="fa fa-foursquare"></i> zblog</a></li>
                            <li><a href="sort/wordpress.htm"><i
                                        class="fa fa-wordpress"></i> wordpress</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="sort/vip.htm" class="dropdown-toggle"
                           data-toggle="dropdown">
                            <i class='fa fa-credit-card'></i> VIP专区 <span class="fa fa-angle-down"></span>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="sort/templates.htm"><i
                                        class="fa fa-shopping-bag"></i> 模板专区</a></li>
                            <li><a href="sort/plugins.htm"><i
                                        class="fa fa-retweet"></i> 插件专区</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="sort/37.htm">
                            <i class='fa fa-gavel'></i> 骗子曝光
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flask"></i>
                            更多功能 <span class="fa fa-angle-down"></span> </a>
                        <ul class="dropdown-menu">
                            <li><a href="contact.html"><i class="fa fa-comment"></i>
                                    留言吐槽</a></li>
                            <li><a href="album.html"><i class="fa fa-picture-o"></i>
                                    相册图库</a></li>
                            <li><a href="-plugin=gs_player.htm"><i
                                        class="fa fa-music"></i> 音乐点播</a></li>
                            <li><a href="archives.html"><i class="fa fa-list"></i>
                                    文章归档</a></li>
                            <li><a href="links.html"><i class="fa fa-link"></i> 我的邻居</a>
                            </li>
                            <li><a href="tools.html"><i class="fa fa-wrench"></i>
                                    站长工具</a></li>
                            <li>
                                <a href="javascript:;" target="_blank"><i class="fa fa-cloud"></i> 个人网盘</a></li>
                            <li><a href="about.html"><i class="fa fa-id-card-o"></i>
                                    关于{{ cnpscy_config('site_web_author') }}</a></li>
                        </ul>
                    </li>
                </div>
            </ul>
            <div class="fly-nav-right display-none">
                <div class="login-nav"><a href="#" class="expand" data-target="#myLogin" data-toggle="modal"
                                          data-backdrop="static" target="_blank"><i class="fa fa-user-circle"></i></a>
                </div>
                <div class="logout-nav" style="display:none"><a href="javascript:;"><span
                            class="login-avatars"></span></a>
                    <div class="avatar-menu">
                        <div class="dltips">
                            <div class="dlli"><a href="javascript:;"><i
                                        class="fa fa-user-circle-o"></i> 修改资料</a></div>
                            <div class="dlli lgset"></div>
                            <div class="dlli logout"><a href="javascript:;" class="login_logout"><i
                                        class="fa fa-sign-out"></i> 退出登录</a></div>
                        </div>
                    </div>
                </div>
                <div class="fly-search fly-search-s"><i class="fa fa-search"></i></div>
            </div>
        </div>
        @endif

    </div>
</nav>
