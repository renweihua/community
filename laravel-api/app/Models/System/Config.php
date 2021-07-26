<?php

namespace App\Models\System;

use App\Models\Model;

/**
 * App\Models\System\Config
 *
 * @property int $config_id 网站配置信息表
 * @property string $config_title 标题
 * @property string $config_name 参数名称
 * @property string $config_value 参数值
 * @property int $config_group 分组
 * @property string $config_extra 配置项
 * @property int $config_type 类型：0.字符串；1.数字；2.文本；3.select下拉框；4.图片；5.富文本
 * @property int $config_sort 排序
 * @property string $config_remark 说明
 * @property int $is_check 是否审核/可用
 * @property \Illuminate\Support\Carbon $created_time 创建时间
 * @property \Illuminate\Support\Carbon $updated_time 更新时间
 * @property int $is_delete 是否删除
 * @method static \Illuminate\Database\Eloquent\Builder|Model filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereConfigValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    protected $primaryKey = 'config_id';
    protected $is_delete = 0;

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
                        list($key, $val, $third) = $ary;
                        $data_list[$key][$val][$third] = $config_value;
                    }else{
                        list($key, $val) = $ary;
                        $data_list[$key][$val] = $config_value;
                    }
                }else{
                    $data_list[$value['config_name']] = $config_value;
                }
            }
        });
        // 文件写入
        $res = file_put_contents( config_path() . '/cnpscy.php', '<?php return ' . var_export($data_list, true) . ';');
        unset($config_data, $_data, $data_list, $array, $value_ary, $data_list);
        return $res;
    }

    /**
     * 后端分组排序列表
     * @return array
     */
    public static function getAdminGroupList()
    {
        $configs = self::where('is_check', 1)->select()->toArray();
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
