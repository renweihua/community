<?php

namespace App\Modules\Admin\Services;

use App\Library\SystemInfo;
use App\Models\Article\Article;
use App\Models\Log\AdminLog;
use App\Models\Rabc\Admin;
use App\Models\System\Banner;
use App\Models\System\Friendlink;
use App\Models\System\Version;
use Illuminate\Support\Facades\Cache;

class IndexService extends BaseService
{
    public function index()
    {
        return [
            // 管理员人数
            'admins_count'      => Admin::count(),
            // 文章总数
            'articles_count'    => Article::count(),
            // Banner数量
            'banners_count'     => Banner::count(),
            // 友情链接数量
            'friendlinks_count' => Friendlink::count(),
            // 技能
            'skill'             => $this->skill(),
            // 统计图：使用请求日志来做数据
            'statistics'        => $this->logsStatistics(),
        ];
    }

    private function skill()
    {
        $arr = [
            'cnpscy'     => 100,
            'PHP'        => rand(70, 99),
            'Mysql'      => rand(70, 99),
            'Redis'      => rand(70, 99),
            'Thinkphp'   => rand(70, 99),
            'Laravel'    => rand(50, 99),
            'Hypref'     => rand(50, 99),
            'Vue'        => rand(10, 99),
            'JavaScript' => rand(20, 99),
        ];
        $list = [];
        foreach ($arr as $key => $value) {
            $list[] = [
                'name'  => $key,
                'value' => $value,
            ];
        }
        return $list;
    }

    /**
     * 按照日志的请求类型来获取对应的统计图数据
     *
     * @return array
     */
    public function logsStatistics()
    {
        $default_time_interval = 300;
        // 时段间隔：5分钟，自己调整
        $time_interval_key = "logs-statistics's-time-interval";
        $time_interval = Cache::get($time_interval_key, $default_time_interval);
        // 通过缓存进行读写
        return Cache::remember('logs-statistics', $time_interval, function() use ($time_interval, $time_interval_key) {
            $default_data = [
                'xAxis'      => [
                    'data' => [],
                ],
                'list_name'  => [
                    // 'GET',
                    'POST',
                    'PUT',
                    'DELETE',
                ],
                'data_lists' => [
                    // 'GET'    => [],
                    'POST'   => [],
                    'PUT'    => [],
                    'DELETE' => [],
                ],
            ];
            $adminLogInstance = AdminLog::getInstance();
            $interval_nums = 100; // 时段次数：100个时间段，自己调整
            $hours = ceil($time_interval / 3600); // 时间间隔设置，超过几小时：查询时的开始时间需要加上才有意义
            $time = strtotime(date('Y-m-d H:i', strtotime('+' . $hours . ' hour')) . ':00');

            // 数据查询
            $list = $adminLogInstance->whereBetWeen('created_time', [
                $time - $interval_nums * $time_interval,
                $time,
            ])->get();

            // 是否存在区间有效的日志记录
            $has_records = false;

            for ($i = 0; $i < $interval_nums; $i++) {
                $end_time = $time - $time_interval;
                // 默认X轴的时段
                $default_data['xAxis']['data'][$i] = date('Y-m-d H:i', $end_time);

                // $default_data['data_lists']['GET'][$i] =
                $default_data['data_lists']['POST'][$i] = $default_data['data_lists']['PUT'][$i] = $default_data['data_lists']['DELETE'][$i] = 0;

                if ( $list ) {
                    foreach ($list as $v) {
                        if ( $v->created_time >= $end_time && $v->created_time <= $time ) {
                            $has_records = true;
                            if ( $v->log_method == 'GET' ) {
                                // ++$default_data['data_lists']['GET'][$i];
                            } elseif ( $v->log_method == 'POST' ) {
                                ++$default_data['data_lists']['POST'][$i];
                            } elseif ( $v->log_method == 'PUT' ) {
                                ++$default_data['data_lists']['PUT'][$i];
                            } elseif ( $v->log_method == 'DELETE' ) {
                                ++$default_data['data_lists']['DELETE'][$i];
                            }
                        }
                    }
                }

                // 把当前的结束时间设置为下一次的开始时间
                $time = $end_time;
            }

            // 当没有记录是，时间间隔慢慢往上扩大两倍，实现统计图效果
            if ( !$has_records ) {
                Cache::put($time_interval_key, $time_interval * 2);
            }

            return (array)$default_data;
        });
    }

    /**
     * 编辑登录管理员信息
     *
     * @param $request
     *
     * @return mixed
     */
    public function updateAdmin($request)
    {
        $update['admin_name'] = $request->input('admin_name');
        $password = $request->input('password', '');
        if ( !empty($password) ) {
            $update['password'] = $password;
        }
        return $request->user()->update($update);
    }

    /**
     * 版本历史记录
     *
     * @return mixed
     */
    public function versionLogs()
    {
        return Version::getInstance()->select('version_name', 'version_number', 'version_content', 'publish_date')->orderBy('version_sort', 'DESC')->orderBy('publish_date', 'DESC')->orderBy('version_id', 'ASC')->get();
    }

    /**
     * 获取服务器状态信息
     *
     * @return array
     */
    public function getServerStatus()
    {
        $systemInfo = new SystemInfo;
        $disk_info = $systemInfo->getDisk();
        $disk = [
            'chart_legend' => ['used', 'free'],
            'chart_series' => [
                [
                    'name' => 'used',
                    'value' => $disk_info['used'],
                ],
                [
                    'name' => 'free',
                    'value' => $disk_info['free'],
                ],
            ],
        ];


        $memory_info = $systemInfo->getMemory();
        $memory = [
            'chart_legend' => ['used', 'free', 'buffer_cache'],
            'chart_series' => [
                [
                    'name' => 'used',
                    'value' => $memory_info['used'],
                ],
                [
                    'name' => 'free',
                    'value' => $memory_info['free'],
                ],
                [
                    'name' => 'buffer_cache',
                    'value' => $memory_info['buffer_cache'],
                ],
            ],
        ];

        $cpu_info = $systemInfo->getCpu();
        $cpu = [
            'chart_legend' => ['used', 'free'],
            'chart_series' => [
                [
                    'name' => 'used',
                    'value' => $cpu_info['usage_ratio'],
                ],
                [
                    'name' => 'free',
                    'value' => 100 - $cpu_info['usage_ratio'],
                ],
            ],
        ];
        return [
            'php_os' => PHP_OS, // 服务器系统
            'system' => is_windows() ? 1 : 0, // 1.windows;0.Linux
            'disk' => $disk, // chart数据
            'disk_info' => $disk_info, // 默认数据
            'memory' => $memory,
            'memory_info' => $memory_info, // 默认数据
            'cpu' => $cpu,
        ];
    }
}
