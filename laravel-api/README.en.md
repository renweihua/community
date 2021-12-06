# 小丑路人社区

#### Description
小丑路人社区

#### Software Architecture
Software architecture description

#### Installation

##### 安装Vue
* 安装 npm 包：`npm install`
* 热更新vue项目：`npm run watch-poll`

    - vue无法执行：可尝试：
        - npm rebuild node-sass
    
##### PHP设置
* 命令行：`composer install`
* 命令行：`cp .env.example .env`
* 命令行，生成 APP_KEY：`php artisan key:generate`
* 命令行，JWT的key：`php artisan jwt:secret`
* 导入根目录sql：`community.sql`
* 请立即执行 `php artisan command:autotablebuild`
    - sql文件基本上不会手动更新，那么使用时如果间隔太久，存在几个按月分表的就会缺失表，执行按月分表，生成当月及下月的分表，则避免数据表不存在报错的问题。


##### 站点配置

- 站点解析目录：`public`
- 访问网址：`你的域名/admin`
- 定时任务：
    - 自动按月分表：`php artisan command:autotablebuild`
    - 或者使用任务调度：`php artisan schedule:run`
        - 后置进程：
            `* * * * * php artisan schedule:run >> /dev/null 2>&1`
- 队列[后置进程]：`php artisan queue:work database --daemon --queue=mail-queue,douyin-queue`
    - mysql存储的[注册邮件]的队列： `php artisan queue:work database --queue=mail-queue`
    - mysql存储的[抖音作者与视频同步]的队列： `php artisan queue:work database --queue=douyin-queue`


##### ES设置
* 启动ES：` .\elasticsearch.bat`
* 初始化ES，创建模板与索引：`php artisan es:init`
* 导入数据：`php artisan scout:import "模型命名空间"`
* 使用ES搜索：
```
    $startTime = Carbon::now()->getPreciseTimestamp(3);
    $articles = Dynamic::search($request->input('search'))->get()->toArray();
    $userTime = Carbon::now()->getPreciseTimestamp(3) - $startTime;
    echo "耗时(毫秒)：{$userTime} \n";
    var_dump($articles);
``` 

#### Instructions

1.  按月、按年分表的模型，皆不可使用 `with`，可使用 `load` 代替,`static::query` 会重新 实例化当前模型，之前设置的分表名称将被替换。
    
    具体原因看代码：
```php
    /**
     * Begin querying a model with eager loading.
     *
     * @param  array|string  $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function with($relations)
    {
        return static::query()->with(
            is_string($relations) ? func_get_args() : $relations
        );
    }
```

#### Contribution

1.  Fork the repository
2.  Create Feat_xxx branch
3.  Commit your code
4.  Create Pull Request


#### Gitee Feature

1.  You can use Readme\_XXX.md to support different languages, such as Readme\_en.md, Readme\_zh.md
2.  Gitee blog [blog.gitee.com](https://blog.gitee.com)
3.  Explore open source project [https://gitee.com/explore](https://gitee.com/explore)
4.  The most valuable open source project [GVP](https://gitee.com/gvp)
5.  The manual of Gitee [https://gitee.com/help](https://gitee.com/help)
6.  The most popular members  [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
