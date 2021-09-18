<?php

declare (strict_types = 1);

namespace App\Model\System;

use App\Model\Model;

/**
 * Class Config
 * 配置管理
 *
 * @package App\Model\System
 */
class Config extends Model
{
    protected $primaryKey = 'config_id';

    /**
     * 配置同步到config文件中
     *
     * @return false|int
     */
    public function pushRefreshConfig()
    {
        $config_data = $this->where('is_check', 1)
            ->select('config_value', 'config_name', 'config_type')
            ->get()
            ->toArray();//字段进行过滤
        $_data = $data_list = [];
        array_walk($config_data, function ($value) use (&$data_list) {
            /**
             * 对于数组格式的处理
             *
             * in_array(strtoupper($value['config_name']), ['CONFIG_GROUP_LIST', 'CONFIG_TYPE_LIST', 'MENU_TYPE_LIST']) ||
             */
            if ($value['config_type'] == 3) {
                $value_ary = array_filter(explode('|', str_replace(["\r", "\r\n", "\n"], '|', $value['config_value'])));
                foreach ($value_ary as $k => $v) {
                    if (empty($value['config_name'])) continue;
                    $array = explode(':', str_replace(["'", '"', "\r", "\r\n", "\n"], '', $v));
                    $_data[$array[0]] = $array[1];
                }
                $data_list[$value['config_name']] = $_data;
            }else{
                /**
                 * 配置项的值，对于不同字符类型的格式进行处理
                 */
                switch ($value['config_type']){
                    case 2: // 数字
                        $config_value = floatval($value['config_value']);
                        break;
                    default:
                        $config_value = $value['config_value'];
                        break;
                }
                /**
                 * 如果存在某一类的设置项：
                 *  如：user.login_days、user.pass 这一类，自动设置为数据格式数据，便于使用config的 . 找到数组下坐标
                 */
                if (strstr($value['config_name'], '.')){
                    $ary = explode('.', $value['config_name']);
                    if (count($ary) > 2){
                        [$key, $val, $third] = $ary;
                        $data_list[$key][$val][$third] = $config_value;
                    }else{
                        [$key, $val] = $ary;
                        $data_list[$key][$val] = $config_value;
                    }
                }else{
                    $data_list[$value['config_name']] = $config_value;
                }
            }
        });
        // 文件写入
        $res = file_put_contents( BASE_PATH . '/config/cnpscy.php', '<?php return ' . var_export($data_list, true) . ';');
        unset($config_data, $_data, $data_list, $array, $value_ary, $data_list);
        return $res;
    }

    /**
     * 后端分组排序列表
     * @return array
     */
    public static function getAdminGroupList()
    {
        $configs = self::where('is_check', 1)->get()->toArray();
        if (!empty($configs)) {
            foreach ($configs as &$v) {
                if (in_array($v['config_type'], [4])){
                    if (!empty($v['config_extra'])) $v['config_extra'] = config_array_analysis($v['config_extra']);
                }
            }
        }
        $configs = array_field_group($configs, 'config_group');//按照配置进行分组
        if (empty($configs[0])) $configs[0] = [];
        return $configs;
    }
}