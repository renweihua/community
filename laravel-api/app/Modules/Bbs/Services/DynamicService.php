<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Exception;
use App\Exceptions\InvalidRequestException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicComment;
use App\Models\Dynamic\DynamicPraise;
use App\Services\Service;

class DynamicService extends Service
{
    /**
     * 首页：发现
     *
     * @param  int  $login_user_id
     * @param       $request
     *
     * @return array
     */
    public function discover(int $login_user_id, $request)
    {
        $lists = $this->getDynamics($request, $login_user_id);
        return $lists;
    }

    /**
     * 首页：关注
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function follows(int $login_user_id)
    {
        $lists = Dynamic::check()
            ->whereHas('fanUser', function($query) use ($login_user_id){
                $query->select('friend_id')->where('user_id', $login_user_id);
            })
            ->with(
                [
                    'userInfo' => function($query) use ($login_user_id) {
                        $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade'])->with([
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
                ]
            )
            ->orderBy('dynamic_id', 'DESC')
            ->paginate($this->getLimit(request()->input('limit', 10)));
        foreach ($lists as $item) {
            // 是否已赞
            $item->is_praise = $login_user_id == 0 ? false : ($item->isPraise ? true : false);
            // 是否已收藏
            $item->is_collection = $login_user_id == 0 ? false : ($item->isCollection ? true : false);
            // 是否关注
            $item->userInfo->is_follow = $login_user_id == 0 ? false : ($item->userInfo->isFollow ? true : false);
            // 是否为登录会员
            $item->userInfo->is_self = $login_user_id == 0 ? false : ($item->user_id == $login_user_id ? true : false);
            unset($item->isPraise, $item->isCollection, $item->userInfo->isFollow);
        }
        $lists = $this->getPaginateFormat($lists);
        return $lists;
    }

    /**
     * 指定会员的动态列表
     *
     * @param       $request
     * @param  int  $user_id
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getDynamicsByUser($request, int $user_id, int $login_user_id = 0)
    {
        return $this->getDynamics($request, $login_user_id, ['user_id' => $user_id]);
    }

    /**
     * 动态列表
     *
     * @param       $request
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function lists($request, int $login_user_id = 0)
    {
        return $this->getDynamics($request, $login_user_id);
    }

    /**
     * 获取动态列表：全部调用此方法
     *
     * @param         $request
     * @param  int    $login_user_id
     * @param  array  $screen_params
     *
     * @return array
     */
    protected function getDynamics($request, int $login_user_id = 0, array $screen_params = [])
    {
        $lists = Dynamic::check()->filter($request->all())
                    ->select('dynamic_id', 'user_id', 'topic_id', 'dynamic_title', 'dynamic_images', 'video_path', 'video_info', 'created_time', 'dynamic_type', 'cache_extends', 'dynamic_content')
                    ->where(function($query)use($screen_params, $request){
                        // 筛选所属会员动态
                        if (isset($screen_params['user_id']) && $screen_params['user_id'] > 0) $query->where('user_id', $screen_params['user_id']);
                        // 筛选动态类型
                        $dynamic_type = (int)$request->input('dynamic_type', -1);
                        if ($dynamic_type > -1) $query->where('dynamic_type', $dynamic_type);
                    })
                    ->where(function ($query) use($login_user_id){
                        $query->where('is_public', 1);
                        if ($login_user_id){
                            $query->orWhere(function ($q) use($login_user_id){
                                $q->where([
                                    'is_public' => 0,
                                    'user_id' => $login_user_id
                                ]);
                            });
                        }
                    })
                    ->with(
                        [
                            'userInfo' => function($query) use ($login_user_id) {
                                $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'user_uuid'])->with([
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
                            'topic'
                        ]
                    )
                    ->orderBy('dynamic_id', 'DESC')
                    ->paginate($this->getLimit(request()->input('limit', 10)));
        foreach ($lists as $item) {
            // 是否已赞
            $item->is_praise = $login_user_id == 0 ? false : ($item->isPraise ? true : false);
            // 是否已收藏
            $item->is_collection = $login_user_id == 0 ? false : ($item->isCollection ? true : false);
            // 是否关注
            $item->userInfo->is_follow = $login_user_id == 0 ? false : ($item->userInfo->isFollow ? true : false);
            // 是否为登录会员
            $item->userInfo->is_self = $login_user_id == 0 ? false : ($item->user_id == $login_user_id ? true : false);
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
            throw new Exception('动态不存在！');
        }
        return $dynamic;
    }

    /**
     * 获取动态详情
     *
     * @param  int  $dynamic_id
     * @param  int  $login_user
     *
     * @return bool
     */
    public function detail(int $dynamic_id, int $login_user_id = 0)
    {
        $dynamic = Dynamic::getDynamicById($dynamic_id, $login_user_id);

        if ( !empty($login_user_id)) {
            $dynamic->load([
                'isPraise' => function($query) use ($login_user_id) {
                    $query->where('user_id', $login_user_id);
                },
                'isCollection' => function($query) use ($login_user_id) {
                    $query->where('user_id', $login_user_id);
                },
            ]);
        }else{
            if ($dynamic->is_public == 0){
                throw new InvalidRequestException('动态不可访问！');
            }
        }

        // 浏览量递增
        $dynamic->update(['cache_extends->reads_num' => $dynamic->cache_extends['reads_num'] + 1]);
        // 是否已赞
        $dynamic->is_praise = $login_user_id == 0 ? false : ($dynamic->isPraise ? true : false);
        // 是否已收藏
        $dynamic->is_collection = $login_user_id == 0 ? false : ($dynamic->isCollection ? true : false);
        // 是否关注
        $dynamic->userInfo->is_follow = $login_user_id == 0 ? false : ($dynamic->userInfo->isFollow ? true : false);
        // 是否为登录会员
        $dynamic->userInfo->is_self = $login_user_id == 0 ? false : ($dynamic->user_id == $login_user_id ? true : false);
        unset($dynamic->isPraise, $dynamic->isCollection, $dynamic->userInfo->isFollow);
        $this->setError('动态详情获取成功！');
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
                'user' => function($query) {
                    $query->with([
                        'userInfo' => function($query) {
                            $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade', 'user_uuid');
                        },
                    ]);
                },
            ])->orderBy('created_time', 'ASC')->paginate(10);

        $result = $this->getPaginateFormat($lists);
        foreach ($result['data'] as &$item){
            $item = $item['user'];
        }
        return $result;
    }

    /**
     * 获取动态的评论列表
     *
     * @param  int  $dynamic_id
     *
     * @return array
     */
    public function getDynamicComments(int $dynamic_id, int $login_user_id = 0, $is_pc = 0)
    {
        if ($is_pc){ // PC端只是就是评论列展示，无需回复树状结构
            $comments = DynamicComment::where('dynamic_id', $dynamic_id)
                ->with([
                    'userInfo' => function($query){
                        $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_uuid');
                    },
                    'hasPraise' => function($query) use($login_user_id){
                        $query->where('user_id', $login_user_id);
                    }
                ])
                ->orderBy('created_time', 'DESC')
                ->paginate(10);
        }else{
            $comments = DynamicComment::where('dynamic_id', $dynamic_id)
                ->where('top_level', 0) // 评论要走多层级，默认查顶级
                ->with([
                    'userInfo' => function($query){
                        $query->select('user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_uuid');
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
                    'hasPraise' => function($query) use($login_user_id){
                        $query->where('user_id', $login_user_id);
                    }
                ])
                ->withCount([
                    'replies'
                ])
                ->orderBy('created_time', 'DESC')
                ->paginate(10);
        }

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
