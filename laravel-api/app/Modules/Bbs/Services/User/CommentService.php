<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\DynamicComment;
use App\Models\Dynamic\DynamicCommentPraise;
use App\Models\Dynamic\DynamicPraise;
use App\Models\System\Notify;
use App\Models\User\UserInfo;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class CommentService extends Service
{
    /**
     * 验证评论是否存在
     *
     * @param  int   $dynamic_id
     * @param  bool  $lock
     *
     * @return bool
     */
    private function checkComment(int $dynamic_id, bool $lock = false, array $with = [])
    {
        $dynamic = DynamicComment::with($with)->lock($lock)->find($dynamic_id);
        if (empty($dynamic)) {
            $this->setError('评论不存在！');
            return false;
        }
        return $dynamic;
    }

    /**
     * 动态：点赞流程
     *
     * @param  int  $login_user_id
     * @param  int  $comment_id
     *
     * @return bool
     */
    public function praise(int $login_user_id, int $comment_id, &$is_cancel = 0) : bool
    {
        if ( !$comment = $this->checkComment($comment_id, true)) {
            return false;
        }
        $commentPraise = DynamicCommentPraise::getInstance();
        $data = [
            'user_id' => $login_user_id,
            'comment_id' => $comment_id,
            'dynamic_id' => $comment->dynamic_id,
        ];
        DB::beginTransaction();
        try {
            // 动态的作者
            $author = $comment->user_id;

            $userInfoInstance = UserInfo::getInstance();
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($commentPraise->isPraise($login_user_id, $comment_id)) {
                $parise_num = -1;
                // 删除点赞记录
                $commentPraise->where($data)->delete();
                // 会员获赞数递减
                $userInfoInstance->setGetLikes($author, -1);

                $this->setError('取消评论点赞成功！');
                $is_cancel = 1;
            } else {
                $ip_agent = get_client_info();
                $commentPraise->create(array_merge($data, [
                    'created_time' => time(),
                    'created_ip' => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                ]));
                // 获赞数递增
                $userInfoInstance->setGetLikes($author, 1);

                // 互动消息：xxx 点赞了您的评论 xxx。
                if ($author != $login_user_id){
                    if (!Notify::insert([
                        'notify_type'  => Notify::NOTIFY_TYPE['INTERACT_MSG'],
                        'user_id'      => $author,
                        'target_id'    => $comment_id,
                        'target_type'  => Notify::TARGET_TYPE['DYNAMIC'],
                        'sender_id'    => $login_user_id,
                        'sender_type'  => Notify::SYSTEM_SENDER,
                        'dynamic_type' => Notify::DYNAMIC_TARGET_TYPE['COMMENT_PRAISE'],
                        'extend_id' => $comment_id,
                    ]) ) {
                        throw new FailException('互动消息录入失败！');
                    }
                }

                $this->setError('评论点赞成功！');
            }

            // 评论的点赞量实时变动（沉余字段）
            $comment->increment('praise_count', $parise_num);

            DB::commit();
            return true;
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }
}
