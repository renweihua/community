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
    public function getDynamics($request, int $user_id, int $login_user = 0)
    {
        $lists = Dynamic::check()
                        ->where('user_id', $user_id)
                        ->with(
                            [
                                'userInfo' => function($query) {
                                    $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade']);
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
            unset($item->isPraise, $item->isCollection);
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
            'userInfo' => function($query) {
                $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade']);
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
        unset($dynamic->isPraise, $dynamic->isCollection);
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
                    ])->limit(5);
                },
            ])
            ->withCount([
                'replies'
            ])
            ->orderBy('created_time', 'DESC')
            ->paginate(10);

        return $this->getPaginateFormat($comments);
    }
}
