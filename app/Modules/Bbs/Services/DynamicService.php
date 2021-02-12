<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicCollection;
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
    private function geyDynamicDetail(int $dynamic_id, bool $lock = false, array $with = [])
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
        if ( !$dynamic = $this->geyDynamicDetail($dynamic_id, false, [
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
     * 动态：点赞流程
     *
     * @param       $user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function praise($user, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->geyDynamicDetail($dynamic_id, true)) {
            return false;
        }
        $dynamicPraise = DynamicPraise::getInstance();
        $data = [
            'user_id' => $user->user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            // 动态的作者
            $author = $dynamic->user_id;

            $userInfoInstance = UserInfo::getInstance();
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicPraise->isPraise($user->user_id, $dynamic_id)) {
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
     * 动态：收藏流程
     *
     * @param       $user
     * @param  int  $dynamic_id
     *
     * @return bool
     */
    public function collection($user, int $dynamic_id) : bool
    {
        if ( !$dynamic = $this->geyDynamicDetail($dynamic_id, true)) {
            return false;
        }
        $dynamicCollection = DynamicCollection::getInstance();
        $data = [
            'user_id' => $user->user_id,
            'dynamic_id' => $dynamic_id,
        ];
        DB::beginTransaction();
        try {
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicCollection->isCollection($user->user_id, $dynamic_id)) {
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
}
