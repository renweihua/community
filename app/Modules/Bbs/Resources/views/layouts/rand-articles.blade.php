@if(!empty($rand_articles))
<li class="widget widget_posts_list aos-init" data-aos='fade-up'>
    <article class="panel-side">
        <header class="panel-header">
            <span class="icon"><i class="fa fa-random"></i></span>
            <h3 class="widget-title">随机文章</h3>
        </header>
        <ul class="sidebar-randlog-list">
            @foreach($rand_articles as $article)
            <li>
                <a href="{{ url('detail/' . $article->article_id) }}" title="{{ $article->article_title }}">
                                <span class="thumbnails">
                                    <span>
                                        <img
                                            src="{{ $article->article_cover }}"
                                            class="thumbs" alt="{{ $article->article_title }}"
                                            onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"
                                            />
                                    </span>
                                </span>
                    <span class="text">{{ $article->article_title }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </article>
</li>
@endif
