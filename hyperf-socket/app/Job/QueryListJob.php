<?php

declare(strict_types = 1);

namespace App\Job;

use Hyperf\AsyncQueue\Job;

class QueryListJob extends Job
{
    public $params;

    /**
     * 任务执行失败后的重试次数，即最大执行次数为 $maxAttempts+1 次
     *
     * @var int
     */
    protected $maxAttempts = 2;

    // 默认请求头部
    protected $headers = [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, br',
        ],
    ];

    // queryList
    protected $query_list;

    // 文件模型
    protected $file_model;

    // 文件下载到的目录
    protected $dir_name = 'uploads';

    public function __construct($params)
    {
        // 这里最好是普通数据，不要使用携带 IO 的对象，比如 PDO 对象
        $this->params = $params;

        // 设置文件下载的目录
        $this->setDirName();
    }

    public function handle()
    {
        var_dump(__CLASS__ . __FUNCTION__ . date('Y-m-d H:i:s'));
        // 根据参数处理具体逻辑
        sleep(3);
        // 通过具体参数获取模型等
        var_dump($this->params);
    }

    public function getAbsolutePath($file_name = '', string $name = '') : string
    {
        $dir_path = BASE_PATH . '/public/' . $this->dir_name . '/' . (empty($file_name) ? : ($file_name . '/'));
        $dir_prefix = '';
        foreach (explode('/', $dir_path) as $dir) {
            $dir_prefix .= '/' . $dir;
            if ( !is_dir($dir_prefix) ) mkdir($dir_prefix, 0755);
        }
        return $dir_path . (empty($name) ? '' : $name);
    }

    /**
     * 图片的访问路径
     *
     * @param  string  $name
     *
     * @return string
     */
    public function getFilePath($file_name = '', string $name) : string
    {
        return '/' . $this->dir_name . '/' . (empty($file_name) ? : ($file_name . '/')) . $name;
    }

    /**
     * 设置文件下载目录
     */
    protected function setDirName($dir_name = ''):void
    {
        if (empty($dir_name)){
            $class_ary = explode('\\', get_class($this));
            $this->dir_name = end($class_ary);
        }else{
            $this->dir_name = $dir_name;
        }
    }
}
