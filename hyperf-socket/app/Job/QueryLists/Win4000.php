<?php
declare(strict_types = 1);

namespace App\Job\QueryLists;

use App\Job\QueryListJob;
use App\Model\Gallery\Gallery;
use App\Model\Gallery\GalleryDetail;
use App\Model\Gallery\GalleryTag;
use App\Model\Gallery\GalleryWithTag;
use App\Model\Upload\UploadFile;
use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class Win4000 extends QueryListJob
{
    const URL           = 'http://www.win4000.com';
    // 默认的美女图片列表页
    const DEFAULT_LIST = self::URL . '/meitu.html';

    // 分类图片列表页
    public function getCategoryListUrl($tag_id = 1, $page = 1)
    {
        return self::URL . '/meinvtag' . $tag_id . '_' . $page . '.html';
    }

    private function getPageByUrl($url)
    {
        $ary = explode('_', str_replace('.html', '', $url));
        return end($ary);
    }

    public function handle()
    {
        var_dump('handle - 开始爬取{' . self::URL . '}数据：' . date('Y-m-d H:i:s'));;
        var_dump('-------------------------------------------');

        if (!empty($this->params)){
            // 文件模型
            $this->file_model = UploadFile::getInstance();

            $this->query_list = QueryList::getInstance();

            /**
             * 只进行单独的标签列表页进行数据爬取，不自动获取所有有效的列表页进行数据爬取（太消耗时间）
             */
            // 可以进行多个标签页进行爬取，换行区分
            $list = explode(PHP_EOL, $this->params['crawling_urls']);
            // 检测标签页面是否存在，记录存在的标签页面
            $tag_list_urls = [];
            foreach ($list as $list_url){
                // 检测标签页面是否存在，一旦页面404去使用queryList去请求，会导致报错，程序中断。
                if (check_http_file_exists($list_url)){
                    $tag_list_urls[] = $list_url;
                }
            }
            if (!empty($tag_list_urls)){
                $gallery_tag_model = GalleryTag::getInstance();
                $gallery_model = Gallery::getInstance();
                $gallery_detail_model = GalleryDetail::getInstance();
                $gallery_with_tag_model = GalleryWithTag::getInstance();
                $key = 1;
                foreach ($tag_list_urls as $tag_list_url){
                    /**
                     * 获取爬取URL的页码，为初始页码
                     */
                    $page = $this->getPageByUrl($tag_list_url);

                    while(!empty($list)){
                        var_dump('$tag_list_url：' . $tag_list_url);
                        // 获取列表数据
                        $list = $this->getListData($tag_list_url);
                        foreach ($list as $item) {
                            var_dump('第' . $key . '个图库：' . $item['gallery_name'] . '（标签：' . $item['tag_name'] . '）');
                            // 检测文章标题是否已存在，不存在录入
                            $gallery_tag = $gallery_tag_model->getGalleryTagByName($item['tag_name']);
                            if (!$gallery_tag) {
                                // 标签名称
                                $gallery_tag = $gallery_tag_model->add([
                                    'tag_name' => $item['tag_name'],
                                    'origin_link' => $item['tag_origin_link']]);
                            }
                            // 录入图库
                            $gallery = $gallery_model->getGalleryByName($item['gallery_name']);
                            if (!$gallery){
                                /**
                                 * 图库封面写入生成文件
                                 */
                                $detail_file_name = $item['tag_name'] . '/' . $item['gallery_name'];
                                // 获取图片资源，并保存图片到本地路径
                                $gallery_cover = $this->getAbsolutePath($detail_file_name, $this->file_model->getUniqidName());
                                file_put_contents($gallery_cover, file_get_contents($item['gallery_cover']));
                                // 图库
                                $gallery = $gallery_model->create([
                                    'gallery_name' => $item['gallery_name'],
                                    'gallery_cover' => $gallery_cover,
                                    'gallery_origin' => $item['gallery_origin'],
                                    'total_num' => $item['detail']['picture_nums'],
                                ]);


                                /**
                                 * 详情所有图片写入生成文件
                                 */
                                $detail = $item['detail'];
                                foreach ($detail['gallery_pictures'] as &$v){
                                    // 生成文件名
                                    $local_file_name = $this->file_model->getUniqidName();
                                    // 获取图片资源，并保存图片到本地路径
                                    $detail_file_name = $item['tag_name'] . '/' . $item['gallery_name'];
                                    file_put_contents($this->getAbsolutePath($detail_file_name, $local_file_name), file_get_contents($v));
                                    $v = $this->getFilePath($detail_file_name, $local_file_name);
                                }

                                // 录入图库详情
                                $gallery_detail_model->create([
                                    'gallery_id' => $gallery->gallery_id,
                                    'gallery_pictures' => my_json_encode($detail['gallery_pictures']),
                                    'picture_nums' => $detail['picture_nums'],
                                    'origin_link' => $detail['origin_link'],
                                ]);
                            }

                            // 标签与图库的关联：一个图库，可以标记多少标签
                            if(!$gallery_with_tag_model->cnpscyDetail([
                                                                         'tag_id' => $gallery_tag->tag_id,
                                                                         'gallery_id' => $gallery->gallery_id,
                                                                     ])){
                                $gallery_with_tag_model->create([
                                                                    'tag_id' => $gallery_tag->tag_id,
                                                                    'gallery_id' => $gallery->gallery_id,
                                                                ]);
                            }

                            ++$key;
                        }

                        // 页码自动自增，查询下一页数据
                        ++$page;

                        // 获取标签页, 下一个页码的url：匹配   【_页码】
                        $tag_list_url = preg_replace("/(_+\d)/", "_" . $page, $tag_list_url);

                        // 释放资源，销毁内存占用
                        QueryList::destructDocuments();
                    }
                }
            }
        }
        var_dump('-------------------------------------------');
        var_dump('handle - 结束：' . date('Y-m-d H:i:s'));

        return;
        $list = $this->getCategoryLists();
    }

    public function getCategoryLists()
    {
        return [
            $this->getCategoryListUrl(),
        ];
        for($i = 1; $i <= 5; $i ++){
            $list[] = [];
        }
        return $list;
    }

    public function getListData($url)
    {
        if (empty($url)) return [];
        // 获取标签的名称
        $tag_name = $this->query_list->get($url)
                             ->rules(['tag_name' => ['h2', 'text']])
                             ->range('div.Left_bar>div.list_cont>div.tit')
                             ->queryData()[0]['tag_name'] ?? ['默认'];

        $rules = [// 采集规则
            'gallery_name' => ['a>p', 'text'], // 名称
            'gallery_cover' => ['a>img', 'data-original'], // 封面
            'gallery_origin' => ['a', 'href'], // 详情链接
        ];
        $query_list = $this->query_list->get($url, null, $this->headers)->rules($rules);
        // 转换URL相对路径到绝对路径 扩展包
        $query_list->use(AbsoluteUrl::class);
        // 用于切分数组的标识（加入是列表：每条数据的标识）
        $range = 'div.w1180>div.Left_bar>div.list_cont>div.tab_tj>div.tab_box>div>ul.clearfix>li';
        // 文件模型
        $file_model = $this->file_model;
        // 当前类变量调用，闭包无法调用 $this
        $this_class = $this;
        // 数组组装
        $list = $query_list->range($range)->queryData(function ($item) use ($query_list, $file_model, $this_class, $tag_name, $url) {
            // 标签的名称
            $item['tag_name'] = $tag_name;
            // 标签的链接来源
            $item['tag_origin_link'] = $url;
            /**
             * 1.需要获取所有的详情页的url
             * 2.进行页面的图片爬取，并记录
             */
            // 获取详情的所有图片的路径
            $detail_urls = $query_list->get($item['gallery_origin'], null, $this->headers)->rules([
                'url' => ['a', 'href']
            ])->range('div.main-wrap>div.scroll-img-cont>ul>li')->queryData();
            // 通过url获取单图的资源
            foreach ($detail_urls as $key => $detail){
                $file_url = $query_list->get($detail['url'], null, $this->headers)->rules([
                    'url' => ['a>img', 'url']
                ])->range('div.main-wrap>div.pic-meinv')->queryData()[0]['url'] ?? '';
                $item['detail']['gallery_pictures'][] = $file_url;
            }
            // 图片的数量
            $item['detail']['picture_nums'] = count($item['detail']['gallery_pictures']);
            $item['detail']['origin_link'] = $item['gallery_origin'];
            return $item;
        });
        return $list;
    }
}
