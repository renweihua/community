<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicCollection;
use App\Models\Dynamic\DynamicComment;
use App\Models\Dynamic\DynamicPraise;
use App\Models\User\UserInfo;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class DynamicService extends Service
{
    /**
     * 发布动态
     *
     * @param  int    $login_user_id
     * @param  array  $params
     *
     * @return bool
     */
    public function push(int $login_user_id, array $params)
    {
        DB::beginTransaction();
        try {
            $ip_agent = get_client_info();
            Dynamic::create([
                'user_id' => $login_user_id,
                'dynamic_content' => $params['dynamic_content'],
                'is_check' => 1, // 暂时默认无需审核
                'is_public' => $params['is_public'] ?? 1,
                'created_ip' => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            ]);
            $this->setError('发布成功！');

            DB::commit();
            return true;
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 验证动态是否存在
     *
     * @param  int   $dynamic_id
     * @param  bool  $lock
     *
     * @return bool
     */
    private function checkDynamic(int $dynamic_id, bool $lock = false, array $with = [])
    {
        $dynamic = Dynamic::check()->with($with)->lock($lock)->find($dynamic_id);
        if (empty($dynamic)) {
            $this->setError('动态不存在！');
            return false;
        }
        return $dynamic;
    }

    /**
     * 获取指定动态的点赞人员记录
     *
     * @param  int  $dynamic_id
     *
     * @return array
     */
    public function getPraises(int $dynamic_id)
    {
        $lists = DynamicPraise::where('dynamic_id', $dynamic_id)
                              ->select('relation_id', 'user_id', 'created_time')
                              ->with([
                                  'userInfo' => function($query) {
                                      $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade');
                                  },
                              ])->orderBy('created_time', 'ASC')->paginate(10);

        return $this->getPaginateFormat($lists);
    }

    /**
     * 动态：点赞流程
     *
     * @param       $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function praise($login_user, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->checkDynamic($dynamic_id, true)) {
            return false;
        }
        $dynamicPraise = DynamicPraise::getInstance();
        $data = [
            'user_id' => $login_user->user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            // 动态的作者
            $author = $dynamic->user_id;

            $userInfoInstance = UserInfo::getInstance();
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicPraise->isPraise($login_user->user_id, $dynamic_id)) {
                $parise_num = -1;
                // 删除点赞记录
                $dynamicPraise->where($data)->delete();
                // 会员获赞数递减
                $userInfoInstance->setGetLikes($author, -1);

                $this->setError('取消点赞成功！');
            } else {
                $ip_agent = get_client_info();
                $dynamicPraise->create(array_merge($data, [
                    'created_time' => time(),
                    'created_ip' => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                ]));
                // 获赞数递增
                $userInfoInstance->setGetLikes($author, 1);

                // 互动消息：xxx 点赞了您的动态 xxx。

                $this->setError('点赞成功！');
            }

            // 动态的点赞量实时变动（沉余字段）
            $dynamic->increment('praise_count', $parise_num);

            DB::commit();
            return true;
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 我的收藏
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getCollections(int $login_user_id)
    {
        $lists = DynamicCollection::where('user_id', $login_user_id)
                                  ->select('relation_id', 'user_id', 'dynamic_id', 'created_time')
                                  ->with([
                                      'dynamic' => function($query) {
                                          $query->select('dynamic_id', 'user_id', 'user_id', 'dynamic_title', 'dynamic_images', 'dynamic_content', 'created_time', 'praise_count', 'collection_count', 'comment_count');
                                      },
                                  ])
                                  ->orderBy('relation_id', 'DESC')
                                  ->paginate(10);

        return $this->getPaginateFormat($lists);
    }

    /**
     * 动态：收藏流程
     *
     * @param       $login_user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function collection($login_user, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->checkDynamic($dynamic_id, true)) {
            return false;
        }
        $dynamicCollection = DynamicCollection::getInstance();
        $data = [
            'user_id' => $login_user->user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicCollection->isCollection($login_user->user_id, $dynamic_id)) {
                $parise_num = -1;
                // 删除点赞记录
                $dynamicCollection->where($data)->delete();
                $this->setError('取消收藏成功！');
            } else {
                $ip_agent = get_client_info();
                $dynamicCollection->create(array_merge($data, [
                    'created_time' => time(),
                    'created_ip' => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                ]));

                // 互动消息：xxx 收藏了您的动态 xxx。

                $this->setError('收藏成功！');
            }

            // 动态的收藏量实时变动（沉余字段）
            $dynamic->increment('collection_count', $parise_num);

            DB::commit();
            return true;
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 评论动态的流程
     *
     * @param $login_user
     * @param $params
     *
     * @return bool
     */
    public function comment($login_user, $params)
    {
        if ( !$dynamic = $this->checkDynamic($params['dynamic_id'], true)) {
            return false;
        }
        $dynamicCommentInstance = DynamicComment::getInstance();
        $reply_id = $params['reply_id'] ?? 0;
        // 如果评论，那么默认就是发布者
        $reply_user = 0;
        $top_level = 0;
        // 验证回复的评论
        if ( !empty($reply_id)) {
            if ( !$detail = $dynamicCommentInstance->where('comment_id', $params['reply_id'])->first()) {
                $this->setError('回复的评论信息不存在');
                return false;
            }
            // 顶级Id
            $top_level = $detail->top_level == 0 ? $detail->comment_id : $detail->top_level;
            $reply_user = $detail->user_id;
        }
        DB::beginTransaction();
        try {
            // 评论信息组装
            $validate_data = [
                'user_id' => $login_user->user_id,
                'reply_user' => $reply_user,
                'dynamic_id' => $dynamic->dynamic_id,
                'reply_id' => $reply_id,
                'top_level' => $top_level,
                'comment_content' => $params['content'],
                'author_id' => $dynamic->user_id,
                // 如果评论者与被回复人是同一个人，那么则默认已读，无需通知
                'is_read' => $login_user->user_id == $reply_user ? 1 : 0,
            ];
            $comment = $dynamicCommentInstance->create($validate_data);

            // 动态的评论量实时变动（沉余字段）
            $dynamic->increment('comment_count');

            // 给动态归属者发送消息：互动消息：谁评论了你的动态

            DB::commit();
            $this->setError('评论成功！');

            // 评论加载发布者
            $comment->userInfo;
            // 默认关联数据设置
            $comment->replies = [];
            $comment->is_praise = false;
            $comment->praise_count = $comment->replies_count = 0;

            return $comment;
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError('评论失败！');
            return false;
        }
    }
}
