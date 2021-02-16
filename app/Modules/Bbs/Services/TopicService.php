<?php

namespace App\Modules\Bbs\Services;

use App\Models\Dynamic\Topic;
use App\Services\Service;

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
}
