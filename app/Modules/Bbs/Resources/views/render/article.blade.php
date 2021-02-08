<!-- 文章列表数据渲染模板 -->
<article class="content-list aos-init" data-aos='fade-up'>
	@if(count($article->article_images) <= 1)
	    <!-- 单图或无图 -->
	    <div class="content-box posts-gallery-box">
	        @if($article->set_top == 1)
	        <i class="top-mark article-mark"></i>
	        @endif
	        @if($article->is_recommend == 1)
	        <i class="hot-mark  article-marks"></i>
	        @endif
	        @if(!empty($article->article_cover))
                <div class="posts-gallery-img">
                    <a class="simg" href="{{ url('detail/' . $article->article_id) }}">
                        <img class="thumbnails lazyload"
                            src="{{ $article->article_cover }}"
                            alt="{{ $article->article_title }}"
                            onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"
                            />
                    </a>
                </div>
	        @endif
	        <div class="@if($article->article_cover) posts-gallery-content @endif">
	            @if(!empty($article->labels))
	            <div class="post-entry-categories">
	                @foreach($article->labels as $label)
	                    <a rel="tag" href="{{ url('/label/' . $label->label_id) }}">{{ $label->label_name }}</a>
	                @endforeach
	            </div>
	            @endif
	            <h2 class="@if($article->set_top == 1 || $article->is_recommend == 1) margin-left-50-px @endif">
	                <a title="{{ $article->article_title }}" href="{{ url('detail/' . $article->article_id) }}">
	                    {{ $article->article_title }}
	                </a>
	                @if(!empty($article->article_images))
                        <small class="text-muted" title="本文{{ count($article->article_images) }}张图片">
                            {{ $article->article_cover }}
                            <?php var_dump($article->article_images);?>
                            <span class="fa fa-picture-o"></span>
                        </small>
	                @endif
	            </h2>
	            <div class="posts-gallery-text @if(empty($article->article_cover)) min-height-50-px @endif">{{ $article->article_description }}</div>
	            <div class="posts-default-info posts-gallery-info">
	                <ul>
	                    <li class="post-author">
	                        <div class="avatar">
	                            <img alt="{{ $article->article_title }}" src="{{ cnpscy_config('site_web_logo') }}" class="avatar avatar-96 photo" height="100" width="100" onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';" />
	                        </div>
	                        <a href="javascript:;" title="查看关于 {{ cnpscy_config('site_web_author') }} 的文章">{{ cnpscy_config('site_web_author') }}</a>
	                    </li>
                        @if(!empty($article->menu))
                            <li class="ico-cat">
                                <i class="fa fa-list"></i>
                                <a href="{{ url('/' . $article->menu->menu_url) }}" title="查看 {{ $article->menu->menu_name }} 下的全部文章"> {{ $article->menu->menu_name }} </a>
                            </li>
                        @endif
	                    <li class="ico-time"><i class="fa fa-clock-o"></i> {{ date('Y-m-d H:i', $article->created_time) }}</li>
	                    <li class="ico-eye"><i class="fa fa-eye"></i> {{ $article->read_num }}</li>
	                    <!--
	                    <li class="ico-like"><i class="fa fa-comments-o"></i> 35</li>
	                    -->
	                </ul>
	            </div>
	        </div>
	    </div>
	@else
	    <!-- 多图 -->
	    <div class="content-box posts-image-box">
	        <div class="posts-default-title">
                @if(!empty($article->labels))
                <div class="post-entry-categories">
                    @foreach($article->labels as $label)
                    <a rel="tag" href="{{ url('/label/' . $label->label_id) }}">{{ $label->label_name }}</a>
                    @endforeach
                </div>
                @endif
	            <h2 class="@if($article->set_top == 1 || $article->is_recommend == 1) margin-left-50-px @endif">
	                <a href="{{ url('detail/' . $article->article_id) }}" title="{{ $article->article_title }}">{{ $article->article_title }}</a>
	                @if(!empty($article->article_images))
	                <small class="text-muted" title="本文{{ count($article->article_images) }}张图片">
	                    <span class="fa fa-picture-o"></span>
	                </small>
	                @endif
	            </h2>
	        </div>
	        @if($article->set_top == 1)
	        <i class="top-mark article-mark"></i>
	        @endif
	        @if($article->is_recommend == 1)
	        <i class="hot-mark  article-marks"></i>
	        @endif
	        @if(!empty($article->article_images))
	        <div class="post-images-item">
	            <ul>
	                @foreach($article->article_images as $key => $image)
                        <!-- 最多只展示3张图即可 -->
                        @if($key > 2)
                            <?php break; ?>
                        @endif
                        <li>
                            <div class="image-item">
                                <a class="simg" href="{{ url('detail/' . $article->article_id) }}">
                                    <img class="thumbnails lazyload"
                                        src="{{ $image }}"
                                        alt="{{ $article->article_title }}"
                                        onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';"
                                        />
                                </a>
                            </div>
                        </li>
	                @endforeach
	            </ul>
	        </div>
	        @endif
	        <div class="posts-default-content">
	            <div class="posts-text">{{ $article->article_description }}</div>
	            <div class="posts-default-info">
	                <ul>
	                    <li class="post-author">
	                        <div class="avatar">
	                            <img alt="{{ $article->article_title }}" src="{{ cnpscy_config('site_web_logo') }}" class="avatar avatar-96 photo" height="100" width="100" onerror="javascript:this.src='{{ cnpscy_config('web_detault_show_img') }}';" />
	                        </div>
	                        <a href="javascript:;" title="查看关于 {{ cnpscy_config('site_web_author') }} 的文章">{{ cnpscy_config('site_web_author') }}</a>
	                    </li>
	                    <li class="ico-cat">
	                        <i class="fa fa-list"></i>
	                        <a href="{{ url('/' . $article->menu->menu_url) }}" title="查看 {{ $article->menu->menu_name }} 下的全部文章"> {{ $article->menu->menu_name }} </a>
	                    </li>
	                    <li class="ico-time"><i class="fa fa-clock-o"></i> {{ date('Y-m-d H:i', $article->created_time) }}</li>
	                    <li class="ico-eye"><i class="fa fa-eye"></i> {{ $article->read_num }}</li>
	                    <!--
	                    <li class="ico-like"><i class="fa fa-comments-o"></i> 35</li>
	                    -->
	                </ul>
	            </div>
	        </div>
	    </div>
	@endif
</article>
