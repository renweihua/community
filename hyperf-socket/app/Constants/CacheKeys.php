<?php

namespace App\Constants;

class CacheKeys
{
    // 默认的缓存时长：默认为1天
    const KEY_DEFAULT_TIMEOUT = 24 * 60 * 60;

    // 文章总量 的 缓存Key
    const CACHE_ARTICLE_COUNT = 'articles_count';

    // 前端菜单栏目的缓存
    const CACHE_WEB_MENUS = 'web_menus';

    // 前端 Banner 的缓存
    const CACHE_WEB_BANNERS = 'web_banners';

    // 前端 友情链接 的缓存
    const CACHE_WEB_FRIENDLINKS = 'web_friendlinks';

    // 前端 标签  的缓存
    const CACHE_WEB_LABELS = 'web_labels';
}
