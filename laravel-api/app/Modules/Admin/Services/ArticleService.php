<?php

namespace App\Modules\Admin\Services;

use App\Exceptions\Exception;
use App\Models\Article\Article;
use App\Models\Article\ArticleCategory;
use App\Models\Article\ArticleWithLabel;
use Illuminate\Support\Facades\DB;

class ArticleService extends BaseService
{
    public function __construct(Article $article)
    {
        $this->model = $article;
        $this->with = ['menu'];
        $this->detailWith = ['labels', 'content'];
    }

    public function lists(array $params) : array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $search = trim($search);
                $query->where('article_title', 'LIKE', '%' . $search . '%')
                    ->whereOr('article_keywords', 'LIKE', $search . '%')
                    ->whereOr('article_description', 'LIKE', $search . '%');
            }
            // 文章分类：包含所有子集的分类筛选
            $category_id = $request->input('category_id', -1);
            if ($category_id > -1){
                $category_ids = ArticleCategory::getInstance()->getChildIds((int)$category_id);
                $query->whereIn('category_id', $category_ids);
            }
            // 置顶
            $set_top = $request->input('set_top', -1);
            if ($set_top > -1){
                $query->where('set_top', '=', $set_top);
            }
            // 推荐
            $is_recommend = $request->input('is_recommend', -1);
            if ($is_recommend > -1){
                $query->where('is_recommend', '=', $is_recommend);
            }
            // 是否公开
            $is_public = $request->input('is_public', -1);
            if ($is_public > -1){
                $query->where('is_public', '=', $is_public);
            }
        };
        return parent::lists($params); // TODO: Change the autogenerated stub
    }

    public function create(array $params)
    {
        DB::beginTransaction();
        try{
            parent::create($params); // TODO: Change the autogenerated stub

            // 新增文章内容
            $ip_agent = get_client_info();
            $this->detail->content()->create([
                'article_id' => $this->detail->article_id,
                'article_content' => $params['article_content'] ?? '',
                'created_ip' => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                ]);

            // 设置文章标签
            $this->setArticleLabels($params);

            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    public function update(array $params)
    {
        DB::beginTransaction();
        try{
            parent::update($params); // TODO: Change the autogenerated stub

            // 更新文章内容
            $this->detail->content()->where('article_id', $this->detail->article_id)->update(['article_content' => $params['article_content'] ?? '']);

            // 设置文章标签
            $this->setArticleLabels($params);

            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    private function setArticleLabels($params)
    {
        if (isset($params['label_ids'])){
            $articleWithLabel = ArticleWithLabel::getInstance();
            $article_id = $this->detail->{$this->model->getKeyName()};
            // 先删除全部的标签
            $articleWithLabel->where('article_id', $article_id)->delete();
            // 关联标签录入
            foreach ($params['label_ids'] as $label){
                $articleWithLabel->create([
                    'label_id' => $label,
                    'article_id' => $article_id,
                ]);
            }
        }
    }
}