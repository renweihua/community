<?php

namespace App\Modules\Admin\Services;

use App\Models\Log\AdminLoginLog;

class AdminLoginLogService extends BaseService
{
    public function __construct(AdminLoginLog $adminLoginLog)
    {
        $this->model = $adminLoginLog;
    }

    public function lists(array $params) : array
    {
        $model = $this->model->setMonthTable($this->getSearchMonth())
            ->load([
                'admin' => function($query) {
                    $query->select('admin_id', 'admin_name');
                },
            ]);
        $admin_id = $params['admin_id'] ?? 0;
        $search = trim($params['search'] ?? '');
        if ( !empty($search) || $admin_id > 0 ) {
            $model = $model->whereHas('admin', function($query) use ($search, $admin_id) {
                if ( !empty($search) ) {
                    $query->where('admin_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('admin_id', '=', intval($search));
                }
                // 指定管理员筛选
                if ( $admin_id > 0 ) {
                    $query->where('admin_id', '=', $admin_id);
                }
            });
        }
        $lists = $model->orderBy($this->model->getKeyName(), 'DESC')
            ->paginate($this->getLimit($params['limit'] ?? 10));

        return [
            'current_page' => $lists->currentPage(),
            'per_page'     => $lists->perPage(),
            'count_page'   => $lists->lastPage(),
            'total'        => $lists->total(),
            'data'         => $lists->items(),
        ];
    }
}
