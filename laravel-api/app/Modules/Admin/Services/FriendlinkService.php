<?php

namespace App\Modules\Admin\Services;

use App\Models\System\Friendlink;

class FriendlinkService extends BaseService
{
    public function __construct(Friendlink $friendlink)
    {
        $this->model = $friendlink;
    }

    public function lists(array $params): array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $query->where('link_name', 'LIKE', '%' . trim($search) . '%');
            }
            // 状态
            $is_check = $request->input('is_check', -1);
            if ($is_check > -1){
                $query->where('is_check', '=', $is_check);
            }
        };
        $params['order'] = 'link_sort';
        $params['order_sort'] = 'ASC';
        return parent::lists($params); // TODO: Change the autogenerated stub
    }
}