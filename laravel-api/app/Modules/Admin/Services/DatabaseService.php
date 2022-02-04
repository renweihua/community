<?php

namespace App\Modules\Admin\Services;

use App\Exceptions\Exception;
use App\Handlers\DatabaseHandler;
use App\Models\TableBackup;
use App\Models\UploadFile;
use Illuminate\Support\Facades\DB;

class DatabaseService extends BaseService
{
    public function __construct(UploadFile $uploadFile)
    {
        $this->model = $uploadFile;
    }

    /**
     * 获取所有的数据表列表
     *
     * @param  array  $params
     *
     * @return array
     */
    public function lists(array $params) : array
    {
        $search = $params['search'] ?? '';
        if ( !empty($search) ) {
            $tables_list = DB::select("SHOW TABLE STATUS LIKE '%" . $search . "%'");
        } else {
            $tables_list = DB::select('SHOW TABLE STATUS');
        }

        $total = 0;
        foreach ($tables_list as $key => $item) {
            if ( $this->checkTableNameRepeat($item->Name) ) {
                unset($tables_list[$key]);
                continue;
            }
            $data_length = (int)$item->Data_length;
            $index_length = (int)$item->Index_length;
            $item->Data_length = format_bytes($data_length);
            $item->Index_length = format_bytes($index_length);
            $plus = $data_length + $index_length;
            $item->Total_length = format_bytes($plus);
            $total += $plus;
        }

        $tables_list = array_values($tables_list);
        return [
            'data'          => $tables_list,
            'table_total'   => count($tables_list),
            'tables_size'   => format_bytes($total),
            'backups_total' => TableBackup::count(),
        ];
    }

    private function duplicateRemoval(&$tables_list) : void
    {
        sort($tables_list);
        foreach ($tables_list as $key => $table) {
            if ( $this->checkTableNameRepeat($table) ) {
                unset($tables_list[$key]);
            }
        }
    }

    private function checkTableNameRepeat(string $table)
    {
        return preg_match("/^\d{4}[\_]([0-9][0-9])?$/", substr($table, -7));
    }

    /**
     * 备份数据表
     *
     * @param  string  $tables_name
     *
     * @return bool
     */
    public function backupsTables($tables_name = '')
    {
        if ( empty($tables_name) ) {
            $tables_list = array_column(DB::select('SHOW TABLE STATUS'), 'Name');
        }else{
            $tables_list = explode(',', $tables_name);
        }

        $this->duplicateRemoval($tables_list);

        $databaseHandler = new DatabaseHandler;
        $res = $databaseHandler->dataTableBak($tables_list, $result);
        $this->error = $databaseHandler->getError();
        if ( $res ) {
            return $result;
        } else {
            throw new Exception($this->error);
        }
    }

    /**
     * 获取备份的数据表记录
     *
     * @return array
     */
    public function getBackupRecords():array
    {
        $lists = TableBackup::OrderBy('backup_id', 'DESC')->paginate($this->getLimit($params['limit'] ?? 10));
        foreach ($lists as $item){
            $tables = explode(',', $item->tables_name);
            // 第一个表名
            // $item->first_table = current($item->tables_name);
            // 表的总量
            $item->tables_total = count($tables);
        }
        return [
            'current_page' => $lists->currentPage(),
            'per_page' => $lists->perPage(),
            'count_page' => $lists->lastPage(),
            'total' => $lists->total(),
            'data' => $lists->items(),
        ];
    }

    /**
     * 删除指定的备份记录，并删除对应的备份文件
     *
     * @param  int  $backup_id
     *
     * @return bool
     */
    public function deleteBackup(int $backup_id)
    {
        $backup = TableBackup::find($backup_id);
        if (empty($backup)){
            $this->error = '备份记录已删除成功！';
            return true;
        }
        if ($backup->delete()){
            // 删除对应的备份文件
            if ($backup->backup_files){
                foreach (explode(',', $backup->backup_files) as $backup_file){
                    @unlink($backup_file);
                }
            }
            $this->error = '备份记录删除成功！';
            return true;
        }else{
            throw new Exception('备份记录删除失败，请重试！');
        }
    }
}
