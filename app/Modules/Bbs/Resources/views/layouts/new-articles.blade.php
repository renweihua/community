@if(!empty($new_articles))
<li class="widget widget_posts_list aos-init" data-aos='fade-up'>
    <article class="panel-side">
        <header class="panel-header">
            <span class="icon"><i class="fa fa-line-chart"></i></span>
            <h3 class="widget-title">最新文章</h3>
        </header>
        <ul class="sidebar-posts-list">
            @foreach($new_articles as $article)
            <li>

                <a href="{{ url('detail/' . $article->article_id) }}" class="thumbnail-link" rel="bookmark">
                    <img src="@if(!empty($article->article_cover)) {{ $article->article_cover }} @else {{ cnpscy_config('site_web_logo') }} @endif"
                         class="thumbnailside"
                         width="50"
                         height="50"
                         title="{{ $article->article_title }}"
                         alt="{{ $article->article_title }}"
                         onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"
                         />
                </a>
                <div class="right-box">
                    <h4 class="side-title">
                        <a href="{{ url('detail/' . $article->article_id) }}"
                           data-toggle="tooltip"
                           data-placement="bottom"
                           title="{{ $article->article_title }}"
                           rel="bookmark">{{ $article->article_title }}</a>
                    </h4>
                    <ul class="side-meta">
                        <li class="date date-abb">
                            <span class="fa fa-clock-o"></span>
                            <a href="{{ url('detail/' . $article->article_id) }}" title="发布于 {{ date('Y-m-d', $article->created_time) }}">
                                <time pubdate="pubdate">{{ date('Y-m-d', $article->created_time) }}</time>
                            </a>
                        </li>
                        <li class="views">
                            <span class="fa fa-eye"></span>
                            <a href="javascript:;" title="浏览了{{ $article->read_num }}次">{{ $article->read_num }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endforeach
        </ul>
    </article>
</li>
@endif
