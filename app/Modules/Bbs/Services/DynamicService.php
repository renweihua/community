<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicPraise;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class DynamicService extends Service
{
    /**
     * 动态详情
     * 
     * @param  int    $dynamic_id
     * @param  bool   $lock
     * @param  array  $with
     *
     * @return bool
     */
    protected function geyDynamicDetail(int $dynamic_id, bool $lock = false, array $with = [])
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
    public function detail(int $dynamic_id)
    {
        if ( !$dynamic = $this->geyDynamicDetail($dynamic_id, false, [
            'userInfo' => function($query) {
                $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade']);
            },
        ])) {
            return false;
        }
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
            $parise_num = 1;
            // 是否已点赞过了该动态
            if ($dynamicPraise->isPraise($user->user_id, $dynamic_id)) {
                $parise_num = -1;
                // 删除点赞记录
                $dynamicPraise->where($data)->delete();
                $this->setError('取消点赞成功！');
            } else {
                $ip_agent = get_client_info();
                $dynamicPraise->create(array_merge($data, [
                    'created_time' => time(),
                    'created_ip' => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                ]));

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
