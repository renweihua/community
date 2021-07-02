<?php

namespace App\Modules\Admin\Services;

use App\Models\Log\AdminLog;

class AdminLogService extends BaseService
{
    public function __construct(AdminLog $adminLog)
    {
        $this->model = $adminLog;
    }

    public function lists(array $params) : array
    {
        $model = $this->model->setMonthTable($this->getSearchMonth())
            ->load([
                'admin' => function($query) {
                    $query->select('admin_id', 'admin_name');
                },
            ]);
        if ( isset($params['search']) && !empty($params['search']) ) {
            $model = $model->whereHas('admin', function($query) use ($params) {
                $search = trim($params['search'] ?? '');
                if ( !empty($search) ) {
                    $query->where('admin_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('admin_id', '=', intval($search));
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
