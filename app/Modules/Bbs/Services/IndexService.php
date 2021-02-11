<?php

namespace App\Modules\Bbs\Services;

use App\Models\Dynamic\Dynamic;
use App\Services\Service;

class IndexService extends Service
{
    /**
     * 发现 - 动态列表
     *
     * @param $user
     * @param $request
     *
     * @return array
     */
    public function discover($user, $request)
    {
        $login_user = $user->user_id ?? 0;
        $lists = Dynamic::check()
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
        foreach ($lists as $item){
            // 是否已赞
            $item->is_praise = $login_user == 0 ? false : ($item->isPraise ? true : false);
            // 是否已收藏
            $item->is_collection = $login_user == 0 ? false : ($item->isCollection ? true : false);
            unset($item->isPraise, $item->isCollection);
        }
        $lists = $this->getPaginateFormat($lists);
        return $lists;
    }
}
