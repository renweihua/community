<?php

namespace App\Handlers;

use App\Models\TableBackup;
use App\Traits\Error;
use Illuminate\Support\Facades\DB;

class DatabaseHandler
{
    use Error;

    protected $data_table_bak_dir = '';

    public function __construct()
    {
        $this->data_table_bak_dir = cnpscy_config('data_table_bak_dir', dirname(dirname(__DIR__)) . '/database/backups');
    }

    /**
     * 数据表备份
     *
     * @param  array  $tables  备份的数据表名
     * @param  int    $admin_id
     *
     * @return bool
     */
    public function dataTableBak(array $tables, &$result, int $admin_id = 0)
    {
        $systemConfig = [
            'max_bak_sql_file_size' => 10,
        ];
        if ( !$systemConfig['max_bak_sql_file_size'] ) {
            $this->error = '管理员未设置备份文件最大大小';
            return false;
        }
        @mkdir($this->data_table_bak_dir);
        if ( !file_exists($this->data_table_bak_dir) ) {
            $this->error = '备份目录：' . $this->data_table_bak_dir . '不存在';
            return false;
        }
        $start_time = microtime(true);//开始时间

        $full_filename = $this->data_table_bak_dir . '/' . env('DB_DATABASE', '') . '=' . date('Y-m-d=His=', $start_time) . rand_str(3);
        $pre = "/* -----------------------------------------------------------*/\n";

        //取得表结构信息
        //1，表示表名和字段名会用``包着的,0 则不用``
        DB::select("SET SQL_QUOTE_SHOW_CREATE = 1");
        $outstr = '';
        foreach ($tables as $k => $v) {
            $outstr .= "/* 表的结构 {$v}*/ \n";
            $outstr .= "DROP TABLE IF EXISTS {$v};\n";
            $tmp = DB::select("SHOW CREATE TABLE {$v}");
            $outstr .= ($tmp[0])->{'Create Table'} . ";\n\n";
        }
        $sqlTable = $outstr;//表结构--建表语句
        $file_n = 1;
        $outstr = "";

        $backed_table = [];//备份的表

        $db_prefix = env('DB_PREFIX');
        //表中的数据
        foreach ($tables as $k => $table_name) {//循环出表名
            $backed_table[] = $table_name;
            $outstr .= "\n\n/* 转存表中的数据:{$table_name}*/ \n";//表中的数据
            $lists = DB::table(str_replace($db_prefix, '', $table_name))->get();//查出每一个表的所有数据
            // var_dump($lists);
            foreach ($lists as $kk => $item) {
                $tn = 0;//表中的第几条数据
                $tem_sql = '';//将每一张表的每条数据拼接起来
                $table_columns = '';
                $data = object_to_array($item);

                $table_columns = '`' . implode('`,`', array_keys($data)) . '`';

                foreach (array_values($data) as $value) {
                    $tem_sql .= $tn == 0 ? "" : ",";
                    $tem_sql .= $table_name == '' ? "''" : "'{$value}'";
                    $tn++;
                }
                $tem_sql = "INSERT INTO `{$table_name}` (" . $table_columns . ") VALUES ({$tem_sql});\n";
                $sql_no = "\n/* Time: " . date("Y-m-d H:i:s", time()) . "*/\n" . "/* -----------------------------------------------------------*/\n" . "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
                if ( $file_n == 1 ) {
                    $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables) . "*/\n" . "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
                } else {//如果不是第一个文件
                    $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
                }
                if ( strlen($pre) + strlen($sql_no) + strlen($sqlTable) + strlen($outstr) + strlen($tem_sql) > (1024 * 1024 * $systemConfig['max_bak_sql_file_size']) ) {//如果超出了每个sql文件的限制
                    $file = $full_filename . "=" . $file_n . ".sql";
                    if ( $file_n == 1 ) {
                        $outstr = $pre . $sql_no . $sqlTable . $outstr;
                    } else {
                        $outstr = $pre . $sql_no . $outstr;
                    }
                    if ( !file_put_contents($file, $outstr, FILE_APPEND) ) {
                        $this->error = "备份文件写入失败！";
                        return false;
                    }
                    $sqlTable = $outstr = "";
                    $backed_table = [];
                    $backed_table[] = $table_name;
                    $file_n++;
                }
                $outstr .= $tem_sql;
            }
        }
        if ( strlen($sqlTable . $outstr) > 0 ) {
            $sql_no = "\n/* Time: " . date("Y-m-d H:i:s", time()) . "*/\n" . "/* -----------------------------------------------------------*/\n" . "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
            if ( $file_n == 1 ) {
                $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables) . "*/\n" . "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
            } else {//如果不是第一个文件
                $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
            }
            $file = $full_filename . "=" . $file_n . ".sql";
            if ( $file_n == 1 ) {
                $outstr = $pre . $sql_no . $sqlTable . $outstr;
            } else {
                $outstr = $pre . $sql_no . $outstr;
            }
            if ( !file_put_contents($file, $outstr, FILE_APPEND) ) {
                $this->error = "备份文件写入失败！";
                return false;
            }
            $file_n++;
        }
        $usetime = round(microtime(true) - $start_time, 3);

        $insert = [
            'admin_id'    => $admin_id,
            'tables_name' => implode(',', $tables),
            'file_num'    => $file_n - 1,
        ];

        $filesize = 0;
        for ($i = 1; $i < $file_n; $i++) {
            $filename = $full_filename . '=' . $i . '.sql';
            $filesize += filesize($filename);
            $insert['backup_files'][] = $filename;
        }
        $insert['backup_files'] = implode(',', $insert['backup_files']);
        $insert['file_size'] = $filesize;
        $insert['created_ip'] = get_client_ip();

        // 录入备份记录
        TableBackup::create($insert);

        $this->error = "备份操作成功，本次备份共生成了" . ($file_n - 1) . "个SQL文件。耗时：{$usetime} 秒";
        $result = [
            'file_num'       => $file_n - 1,
            'use_time'       => $usetime,
            'base_file_name' => $full_filename,
        ];
        return true;
    }
}
