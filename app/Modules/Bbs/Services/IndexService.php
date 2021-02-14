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
                                   'userInfo' => function($query) use($login_user){
                                       $query->select(['user_id', 'nick_name', 'user_avatar', 'user_sex', 'user_grade'])->with([
                                           'isFollow' => function($query) use ($login_user) {
                                               $query->where('user_id', $login_user);
                                           },
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
        foreach ($lists as $item){
            // 是否已赞
            $item->is_praise = $login_user == 0 ? false : ($item->isPraise ? true : false);
            // 是否已收藏
            $item->is_collection = $login_user == 0 ? false : ($item->isCollection ? true : false);
            // 是否关注
            $item->userInfo->is_follow = $login_user == 0 ? false : ($item->userInfo->isFollow ? true : false);
            unset($item->isPraise, $item->isCollection, $item->userInfo->isFollow);
        }
        $lists = $this->getPaginateFormat($lists);
        return $lists;
    }
}
