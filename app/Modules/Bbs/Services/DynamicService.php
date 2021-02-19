<?php

namespace App\Modules\Bbs\Services;

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
     * 指定会员的动态列表
     *
     * @param       $request
     * @param  int  $user_id
     * @param  int  $login_user
     *
     * @return array
     */
    public function getDynamicsByUser($request, int $user_id, int $login_user = 0)
    {
        return $this->getDynamics($request, $user_id);
    }

    public function getDynamics($request, int $user_id = 0, int $login_user = 0)
    {
        $lists = Dynamic::check()
                        ->where(function($query)use($user_id){
                            if ($user_id) $query->where('user_id', $user_id);
                        })
                        ->with(
                            [
                                'userInfo' => function($query) use ($login_user) {
                                    $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade'])->with([
                                        'isFollow' => function($query) use ($login_user) {
                                            $query->where('user_id', $login_user);
                                        }
                                    ]);
                                },
                                'isPraise' => function($query) use ($login_user) {
                                    $query->where('user_id', $login_user);
                                },
                                'isCollection' => function($query) use ($login_user) {
                                    $query->where('user_id', $login_user);
                                },
                            ]
                        )
                        ->orderBy('dynamic_id', 'DESC')
                        ->paginate($this->getLimit($request->input('limit', 10)));
        foreach ($lists as $item) {
            // 是否已赞
            $item->is_praise = $login_user == 0 ? false : ($item->isPraise ? true : false);
            // 是否已收藏
            $item->is_collection = $login_user == 0 ? false : ($item->isCollection ? true : false);
            // 是否关注
            $item->userInfo->is_follow = $login_user == 0 ? false : ($item->userInfo->isFollow ? true : false);
            // 是否为登录会员
            $item->userInfo->is_self = $login_user == 0 ? false : ($item->user_id == $login_user ? true : false);
            unset($item->isPraise, $item->isCollection, $item->userInfo->isFollow);
        }
        $lists = $this->getPaginateFormat($lists);
        return $lists;
    }

    /**
     * 动态详情
     *
     * @param  int    $dynamic_id
     * @param  bool   $lock
     * @param  array  $with
     *
     * @return bool
     */
    private function getDynamicDetail(int $dynamic_id, bool $lock = false, array $with = [])
    {
        $dynamic = Dynamic::check()->with($with)->lock($lock)->find($dynamic_id);
        if (empty($dynamic)) {
            $this->setError('动态不存在！');
            return false;
        }
        return $dynamic;
    }

    /**
     * 获取动态详情
     *
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function detail(int $dynamic_id, int $login_user = 0)
    {
        if ( !$dynamic = $this->getDynamicDetail($dynamic_id, false, [
            'userInfo' => function($query) use($login_user){
                $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade'])->with([
                    'isFollow' => function($query) use ($login_user) {
                        $query->where('user_id', $login_user);
                    }
                ]);
            },
            'isPraise' => function($query) use ($login_user) {
                $query->where('user_id', $login_user);
            },
            'isCollection' => function($query) use ($login_user) {
                $query->where('user_id', $login_user);
            },
        ])) {
            return false;
        }
        // 是否已赞
        $dynamic->is_praise = $login_user == 0 ? false : ($dynamic->isPraise ? true : false);
        // 是否已收藏
        $dynamic->is_collection = $login_user == 0 ? false : ($dynamic->isCollection ? true : false);
        // 是否关注
        $dynamic->userInfo->is_follow = $login_user == 0 ? false : ($dynamic->userInfo->isFollow ? true : false);
        // 是否为登录会员
        $dynamic->userInfo->is_self = $login_user == 0 ? false : ($dynamic->user_id == $login_user ? true : false);
        unset($dynamic->isPraise, $dynamic->isCollection, $dynamic->userInfo->isFollow);
        $this->setError('动态详情获取成功！');
        return $dynamic;
    }

    /**
     * 获取动态的评论列表
     *
     * @param  int  $dynamic_id
     *
     * @return array
     */
    public function getDynamicComments(int $dynamic_id)
    {
        $comments = DynamicComment::where('dynamic_id', $dynamic_id)
            ->where('top_level', 0) // 评论要走多层级，默认查顶级
            ->with([
                'userInfo' => function($query){
                    $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                },
                'replies' => function($query){
                    $query->with([
                        'userInfo' => function($query){
                            $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                        },
                        'replyUser' => function($query){
                            $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                        },
                    ]);
                },
            ])
            ->withCount([
                'replies'
            ])
            ->orderBy('created_time', 'DESC')
            ->paginate(10);

        return $this->getPaginateFormat($comments);
    }

    /**
     * 加载指定评论的更多回复记录
     *
     * @param  int  $comment_id
     *
     * @return array
     */
    public function loadMoreReplyByComments(int $comment_id)
    {
        $lists = DynamicComment::where('top_level', $comment_id) // 评论要走多层级，默认查顶级
                ->with([
                    'userInfo' => function($query){
                        $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                    },
                    'replyUser' => function($query){
                        $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex');
                    },
                ])
                ->orderBy('comment_id', 'ASC')
                ->paginate(10);

        return $this->getPaginateFormat($lists);
    }
}
