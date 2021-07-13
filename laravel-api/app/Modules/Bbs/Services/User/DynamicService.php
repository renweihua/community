<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicCollection;
use App\Models\Dynamic\DynamicComment;
use App\Models\Dynamic\DynamicPraise;
use App\Models\System\Notify;
use App\Models\User\UserInfo;
use App\Services\Service;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        switch (intval($params['dynamic_type'])){
            case 0: // 动态
                if (!isset($params['dynamic_content']) || empty($params['dynamic_content'])){
                    $this->setError('请输入动态内容！');
                    return false;
                }
                break;
            case 1: // 文章
                if (!isset($params['dynamic_title']) || empty($params['dynamic_title'])){
                    $this->setError('请输入文章标题！');
                    return false;
                }
                if (!isset($params['dynamic_content']) || empty($params['dynamic_content'])){
                    $this->setError('请输入动态内容！');
                    return false;
                }
                break;
            case 2: // 视频
                if (!isset($params['dynamic_title']) || empty($params['dynamic_title'])){
                    $this->setError('请输入标题！');
                    return false;
                }
                if (!isset($params['video_path']) || empty($params['video_path'])){
                    $this->setError('请上传视频！');
                    return false;
                }

                // 通过 ffmpeg 获取视频的第一帧作为封面图
                $params['dynamic_images'] = date('Ym') . '/' . Str::random(40) . '.jpg';
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries'  => '/www/server/ffmpeg-4.3.1/ffmpeg',
                    'ffprobe.binaries' =>  '/www/server/ffmpeg-4.3.1/ffprobe'
                ]);
                $video = $ffmpeg->open($params['video_path']);
                // 获取封面图
                $video->frame(TimeCode::fromSeconds(1))->save(storage_path('app/public/' . $params['dynamic_images']));
                $video_info = $video->getFormat();
                // 存储视频的时长与大小：时长直接向上取整
                $params['video_info'] = ['duration' => round($video_info->get('duration')), 'size' => $video_info->get('size')];

                break;
            case 3: // 相册
                if (!isset($params['dynamic_title']) || empty($params['dynamic_title'])){
                    $this->setError('请输入相册标题！');
                    return false;
                }
                if (!isset($params['dynamic_images']) || empty($params['dynamic_images'])){
                    $this->setError('请上传相册图片！');
                    return false;
                }
                break;
        }
        DB::beginTransaction();
        try {
            $ip_agent = get_client_info();
            Dynamic::create([
                'user_id' => $login_user_id,
                'dynamic_title' => $params['dynamic_title'] ?? '',
                'dynamic_type' => $params['dynamic_type'],
                'dynamic_images' => $params['dynamic_images'] ?? '',
                'video_path' => $params['video_path'] ?? '',
                'video_info' => $params['video_info'] ?? '',
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
     * 动态：点赞流程
     *
     * @param  int  $login_user_id
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function praise(int $login_user_id, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->checkDynamic($dynamic_id, true)) {
            return false;
        }
        $dynamicPraise = DynamicPraise::getInstance();
        $data = [
            'user_id' => $login_user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            // 动态的作者
            $author = $dynamic->user_id;

            $userInfoInstance = UserInfo::getInstance();
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicPraise->isPraise($login_user_id, $dynamic_id)) {
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
                if ($author != $login_user_id){
                    if (!Notify::insert([
                        'notify_type'  => Notify::NOTIFY_TYPE['INTERACT_MSG'],
                        'user_id'      => $author,
                        'target_id'    => $dynamic_id,
                        'target_type'  => Notify::TARGET_TYPE['DYNAMIC'],
                        'sender_id'    => $login_user_id,
                        'sender_type'  => Notify::SYSTEM_SENDER,
                        'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['PRAISE'],
                    ]) ) {
                        throw new FailException('互动消息录入失败！');
                    }
                }

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
                                      'dynamic' => function($query) use($login_user_id){
                                          $query->select('dynamic_id', 'user_id', 'user_id', 'dynamic_title', 'dynamic_images', 'dynamic_content', 'created_time', 'praise_count', 'collection_count', 'comment_count', 'dynamic_type', 'topic_id')
                                              ->with([
                                                  'userInfo' => function($query) use($login_user_id){
                                                      $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex')->with([
                                                          'isFollow' => function($query) use ($login_user_id) {
                                                              $query->where('user_id', $login_user_id);
                                                          }
                                                      ]);
                                                  },
                                                  'isPraise' => function($query) use ($login_user_id) {
                                                      $query->where('user_id', $login_user_id);
                                                  },
                                                  'isCollection' => function($query) use ($login_user_id) {
                                                      $query->where('user_id', $login_user_id);
                                                  },
                                              ]);
                                      },
                                  ])
                                  ->orderBy('relation_id', 'DESC')
                                  ->paginate(10);
        foreach ($lists as $item) {
            // 是否已赞
            $item->dynamic->is_praise = $login_user_id == 0 ? false : ($item->dynamic->isPraise ? true : false);
            // 是否已收藏
            $item->dynamic->is_collection = $login_user_id == 0 ? false : ($item->dynamic->isCollection ? true : false);
            // 是否关注
            $item->dynamic->userInfo->is_follow = $login_user_id == 0 ? false : ($item->dynamic->userInfo->isFollow ? true : false);
            // 是否为登录会员
            $item->dynamic->userInfo->is_self = $login_user_id == 0 ? false : ($item->dynamic->user_id == $login_user_id ? true : false);
            unset($item->dynamic->isPraise, $item->dynamic->isCollection, $item->dynamic->userInfo->isFollow);
        }
        return $this->getPaginateFormat($lists);
    }

    /**
     * 动态：收藏流程
     *
     * @param  int  $login_user_id
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function collection(int $login_user_id, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->checkDynamic($dynamic_id, true)) {
            return false;
        }
        $dynamicCollection = DynamicCollection::getInstance();
        $data = [
            'user_id' => $login_user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicCollection->isCollection($login_user_id, $dynamic_id)) {
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
                if ($dynamic->user_id != $login_user_id){
                    if (!Notify::insert([
                        'notify_type'  => Notify::NOTIFY_TYPE['INTERACT_MSG'],
                        'user_id'      => $dynamic->user_id,
                        'target_id'    => $dynamic_id,
                        'target_type'  => Notify::TARGET_TYPE['DYNAMIC'],
                        'sender_id'    => $login_user_id,
                        'sender_type'  => Notify::SYSTEM_SENDER,
                        'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['COLLECTION'],
                    ]) ) {
                        throw new FailException('互动消息录入失败！');
                    }
                }

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
     * @param  int    $login_user_id
     * @param  array  $params
     *
     * @return bool
     */
    public function comment(int $login_user_id, array $params)
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
                'user_id' => $login_user_id,
                'reply_user' => $reply_user,
                'dynamic_id' => $dynamic->dynamic_id,
                'reply_id' => $reply_id,
                'top_level' => $top_level,
                'content_type' => $params['content_type'],
                'comment_content' => $params['content'] ?? '',
                'comment_markdown' => $params['markdown'] ?? '',
                'author_id' => $dynamic->user_id,
                // 如果评论者与被回复人是同一个人，那么则默认已读，无需通知
                'is_read' => $login_user_id == $reply_user ? 1 : 0,
            ];
            $comment = $dynamicCommentInstance->create($validate_data);

            // 动态的评论量实时变动（沉余字段）
            $dynamic->increment('comment_count');

            // 给动态归属者发送消息：互动消息：谁评论了你的动态
            if ($dynamic->user_id != $login_user_id){
                if (!Notify::insert([
                    'notify_type'  => Notify::NOTIFY_TYPE['INTERACT_MSG'],
                    'user_id'      => $dynamic->user_id,
                    'target_id'    => $dynamic->dynamic_id,
                    'target_type'  => Notify::TARGET_TYPE['DYNAMIC'],
                    'sender_id'    => $login_user_id,
                    'sender_type'  => Notify::SYSTEM_SENDER,
                    'dynamic_type' => $reply_id == 0 ? Notify::DYNAMIC_TARGET_TYPE['COMMENT'] : Notify::DYNAMIC_TARGET_TYPE['REPLY_COMMENT'],
                ]) ) {
                    throw new FailException('互动消息录入失败！');
                }
            }

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

    /**
     * 删除评论流程
     *
     * @param  int  $login_user_id
     * @param  int  $comment_id
     *
     * @return array|bool
     */
    public function deleteComment(int $login_user_id, int $comment_id)
    {
        $dynamicCommentInstance = DynamicComment::getInstance();
        $comment = $dynamicCommentInstance->with('dynamic')->lock(true)->find($comment_id);
        if ( !$comment) {
            $this->setError('该评论不存在，请刷新重试！');
            return false;
        }
        if (!$comment->dynamic || $comment->dynamic->is_delete == 1 || $comment->dynamic->is_check != 1){
            $this->setError('动态已失效！');
            return false;
        }
        if ($comment->author_id != $login_user_id){
            $this->setError('您无权删除！');
            return false;
        }
        DB::beginTransaction();
        try {
            $delete_filed = $dynamicCommentInstance->getDeleteField();
            // 删除评论
            $comment->{$delete_filed} = 1;
            $comment->save();
            // 获取该评论下的所有回复记录
            $reply_lists = DB::select('SELECT * FROM
              (
                SELECT * FROM ' . env('DB_PREFIX') . $dynamicCommentInstance->getTable() . ' where reply_id > 0 ORDER BY reply_id, comment_id DESC
              ) realname_sorted,
              (SELECT @pv := ?) initialisation
              WHERE (FIND_IN_SET(reply_id,@pv)>0 And @pv := concat(@pv, \',\', comment_id)) AND is_delete = 0', [$comment->comment_id]);
            $reply_ids = [];
            if ( !empty($reply_lists) ) {
                foreach ($reply_lists as $reply) {
                    $reply_ids[] = $reply->comment_id;
                }
                // 评论的所有回复记录：批量假删除
                $dynamicCommentInstance->whereIn('comment_id', $reply_ids)->update([$delete_filed => 1]);
            }

            // 动态的评论量实时变动（沉余字段）
            // 评论数量累减：count($reply_ids) + 1 是表示：评论与回复做占用的总条数
            $comment->dynamic->decrement('comment_count', count($reply_ids) + 1);

            DB::commit();
            $this->setError('评论删除成功！');
            return array_merge($reply_ids, [$comment_id]);
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError('评论删除失败！');
            return false;
        }
    }
}
