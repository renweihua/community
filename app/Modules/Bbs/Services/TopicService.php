<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\Topic;
use App\Models\Dynamic\TopicFollow;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class TopicService extends Service
{
    /**
     * 荟吧列表
     *
     * @return mixed
     */
    public function lists()
    {
        $lists = Topic::orderBy('topic_sort', 'ASC')->get();
        return $lists;
    }

    /**
     * 荟吧详情
     *
     * @param  int   $topic_id
     * @param  bool  $lock
     *
     * @return bool
     */
    private function getDetail(int $topic_id, bool $lock = false)
    {
        $detail = Topic::lock($lock)->find($topic_id);
        if (empty($detail)) {
            $this->setError('荟吧不存在！');
            return false;
        }
        return $detail;
    }

    /**
     * 获取荟吧详情
     *
     * @param  int  $topic_id
     *
     * @return bool
     */
    public function detail(int $topic_id, int $login_user = 0)
    {
        if ( !$detail = $this->getDetail($topic_id, false, [
            'isFollow' => function($query) use ($login_user) {
                $query->where('user_id', $login_user);
            },
        ])) {
            return false;
        }
        // 是否已关注
        $detail->is_follow = $login_user == 0 ? false : ($detail->isFollow ? true : false);
        unset($detail->isFollow);
        $this->setError('荟吧详情获取成功！');
        return $detail;
    }

    /**
     * 指定荟吧的动态列表
     *
     * @param       $request
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function dynamics($request, int $login_user_id = 0)
    {
        $lists = Dynamic::check()
            ->where('topic_id', $request->input('topic_id'))
            ->with(
                [
                    'userInfo' => function($query) use($login_user_id){
                        $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade'])->with([
                            'isFollow' => function($query) use ($login_user_id) {
                                $query->where('user_id', $login_user_id);
                            },
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
            ->paginate($this->getLimit($request->input('limit', 10)));
        foreach ($lists as $item){
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
     * 关注荟吧详情
     *
     * @param  int  $login_user_id
     * @param  int  $topic_id
     *
     * @return bool|bool[]
     */
    public function setFollow(int $login_user_id, int $topic_id)
    {
        $topicFollowFan = TopicFollow::getInstance();
        // 获取当前荟吧详情
        $topic = Topic::lock(true)->find($topic_id);
        if (!$topic){
            $this->setError('荟吧详情获取失败！');
            return false;
        }
        // 登录会员是否关注荟吧
        $follow = $topicFollowFan->checkFollow($login_user_id, $topic_id);

        $msg = $follow ? '取关' : '关注';
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $login_user_id,
                'topic_id' => $topic_id,
            ];
            if ($follow) {
                // 删除我关注荟吧记录
                $follow->delete();

                // 荟吧记录，关注人数：递减
                $topic->decrement('follow_count');
            } else {
                // 关注荟吧记录
                $topicFollowFan->create(array_merge($data, [
                    'created_time' => time(),
                ]));

                // 荟吧记录，关注人数：递增
                $topic->increment('follow_count');

                // 互动消息：我关注了荟吧<xxx>
            }

            DB::commit();
            $this->setError($msg . '成功！');
            return ['is_follow' => $follow ? false : true];
        } catch (FailException $e) {
            DB::rollBack();
            $this->setError($msg . '失败！');
            return false;
        }
    }

    /**
     * 指定会员关注的荟吧列表
     *
     * @param  int  $login_user_id
     *
     * @return array
     */
    public function getFollows(int $login_user_id)
    {
        $lists = TopicFollow::where('user_id', $login_user_id)->with('topic')->paginate($this->getLimit(request()->input('limit', 10)));
        return $this->getPaginateFormat($lists);
    }
}
