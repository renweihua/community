<?php

declare(strict_types = 1);

namespace App\Library;

/**
 * Class SystemInfo
 *
 * 服务器状态信息获取：CPU、磁盘、内存
 *
 * @package App\Library
 */
class SystemInfo
{
    // 磁盘
    private $disk = [
        // 总磁盘
        'total'       => 0,
        // 空闲磁盘
        'free'        => 0,
        // 已使用的磁盘
        'used'        => 0,
        // 磁盘占用率
        'usage_ratio' => 0,

        // 单位符
        'unit_symbol' => 'G',
    ];

    /**
     * 获取磁盘相关数据
     *
     * @return array
     */
    public function getDisk()
    {
        if ( is_windows() ) {  //windows服务器
            // 总磁盘(kb -> k -> M -> G)
            $this->disk['total'] = round(disk_total_space("C:") / 1024 / 1024 / 1024, 2);
            $this->disk['free'] = round(disk_free_space("C:") / 1024 / 1024 / 1024, 2);
            $this->disk['used'] = round($this->disk['total'] - $this->disk['free'], 2);
            $this->disk['usage_ratio'] = round(round($this->disk['free'] / $this->disk['total'], 4) * 100, 2);
        } else {
            //获取磁盘占用率
            $fp = popen('df -lh | grep -E "^(/)"', "r");
            $rs = fread($fp, 1024);
            pclose($fp);
            $rs = preg_replace('/\s{2,}/', ' ', $rs);  //把多个空格换成 “_”
            $hd = explode(" ", $rs);
            $this->disk['total'] = trim($hd[1], 'G');//可用空间G
            $this->disk['used'] = trim($hd[2], 'G');//可用空间G
            $this->disk['free'] = trim($hd[3], 'G');//可用空间G
            $this->disk['usage_ratio'] = trim($hd[4], '%');//已使用百分比
        }
        return $this->disk;
    }

    // 内存
    private $memory = [
        // 总内存
        'total'       => 0,
        // 空闲内存
        'free'        => 0,
        // 已使用的内存
        'used'        => 0,
        // 内存占用率
        'usage_ratio' => 0,
        // 单位符
        'unit_symbol' => 'M',
    ];

    /**
     * 获取内存相关数据
     *
     * @return array
     */
    public function getMemory()
    {
        if ( is_windows() ) {  // windows服务器
            $path = $this->getMemoryUsageVbsPathByWindows();
            exec("cscript -nologo $path", $usage);
            $memory = my_json_decode($usage[0], true);

            $this->memory['total'] = round($memory['TotalVisibleMemorySize'] / 1024, 2);
            $this->memory['free'] = round($memory['FreePhysicalMemory'] / 1024, 2);
            $this->memory['buffer_cache'] = 0;
            $this->memory['used'] = round($this->memory['total'] - $this->memory['free'], 2);
            $this->memory['usage_ratio'] = round(round($this->memory['used'] / $this->memory['total'], 4) * 100, 2);
        } else {
            //内存使用率
            $fp = popen('top -b -n 2 | grep -E "(Mem)"', "r");
            $rs = fread($fp, 1024);
            $sys_info = explode("\n", $rs);
            $mem_info = explode(",", $sys_info[2]); //内存占有量 数组

            $this->memory['total'] = round(trim(trim($mem_info[0], 'KiB Mem : '), ' total'), 0);
            $this->memory['used'] = round(trim(trim($mem_info[2], 'used')), 0);
            $this->memory['buffer_cache'] = trim(trim($mem_info[3], 'buff/cache'));
            $this->memory['free'] = round(trim(trim($mem_info[1], 'free')), 0);
            $this->memory['usage_ratio'] = round($this->memory['used'] / $this->memory['total'], 4) * 100; //百分比
        }
        return $this->memory;
    }

    // Cpu
    private $cpu = [
        // 总Cpu
        'total'       => 0,
        // 空闲Cpu
        'free'        => 0,
        // 已使用的Cpu
        'used'        => 0,
        // Cpu占用率
        'usage_ratio' => 0,
    ];

    /**
     * 获取Cpu相关数据
     *
     * @return array
     */
    public function getCpu()
    {
        if ( is_windows() ) {  // windows服务器
            // WINDOWS的CPU是极为不准确的。
            $this->cpu['usage_ratio'] = (float)$this->getCpuUsage();
        } else {
            //获取CPU使用率以及内存使用率
            $fp = popen('top -b -n 2 | grep -E "(Cpu)"', "r");
            $rs = fread($fp, 1024);
            $sys_info = explode("\n", $rs);
            $this->cpu['usage_ratio'] = (float)trim(current(explode(',', trim($sys_info[0], '%Cpu(s): '))), 'us'); //百分比;
        }
        return $this->cpu;
    }

    /**
     * 获得总内存及可用物理内存JSON vbs文件生成函数
     *
     * @return string 返回vbs文件路径
     */
    private function getMemoryUsageVbsPathByWindows()
    {
        return $this->getFilePath('memory_usage.vbs', "On Error Resume Next
    Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
    Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
    For Each objOS in colOS
     Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
    Next");
    }

    /**
     * 判断指定路径下指定文件是否存在，如不存在则创建
     *
     * @param  string  $fileName  文件名
     * @param  string  $content   文件内容
     *
     * @return string 返回文件路径
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if ( !file_exists($path) ) {
            file_put_contents($path, $content);
        }
        return $path;
    }

    /**
     * 获得CPU使用率
     *
     * @return Number
     */
    private function getCpuUsage()
    {
        $path = $this->getCupUsageVbsPathByWindows();
        exec("cscript -nologo $path", $usage);
        return $usage[0];
    }

    /**
     * 获得cpu使用率vbs文件生成函数
     *
     * @return string 返回vbs文件路径
     */
    private function getCupUsageVbsPathByWindows()
    {
        return $this->getFilePath('cpu_usage.vbs', "On Error Resume Next
    Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
    WScript.Echo(objProc.LoadPercentage)");
    }
}
