# 小丑路人社区

#### 介绍
小丑路人社区

#### 软件架构

* 编程语言：`PHP7.3+`
* 后端框架： `Laravel8`
* 前端Vue框架：`vue-element-admin`
* Nodejs  v14.*

#### 安装教程

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
- 队列[后置进程]：`php artisan queue:work database --daemon --queue=mail-queue`
    - mysql存储的[注册邮件]的队列： `php artisan queue:work database --queue=mail-queue`
- 一键生成模型、控制器、验证器与服务层
    - php artisan make:modular 名称 模块


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
* ES参考：https://learnku.com/articles/59076
    - 索引配置
        - `php artisan elastic:create-index  "App\Elasticsearch\IndexConfigurators\DynamicIndexConfigurator"`
    - 在模型中设置映射后，可以更新 Elasticsearch 类型映射，也就是把我们刚才创建的索引和商品的模型绑定在一起
        - ` php artisan elastic:update-mapping "App\Models\Dynamic\Dynamic"`
    - 数据库的数据导入到 Elasticsearch 中
        - `php artisan scout:import "App\Models\Dynamic\Dynamic"`
    - 使用
        - `Dynamic::search('关键字搜索')->paginate();`


##### linux系统下，Laravel使用 env 读取环境变量为 null 的问题

###### 原由：`php artisan config:cache`
    Laravel 将会把 app/config 目录下的所有配置文件“编译”整合成一个缓存配置文件到 bootstrap/cache/config.php，每个配置文件都可以通过 env 函数读取环境变量；但是一旦有了这个缓存配置文件，在其他地方使用 env 函数是读取不到环境变量的，所以返回 null。

###### 解决方式
* 1.`php artisan config:clear` 不启用配置缓存
* 2.使用`config()` 替代 `env()` 读取对应实际的配置即可


###### 部署优化
    
* 配置信息缓存 artisan config:cache
* 路由缓存 artisan route:cache
* 类映射加载优化 artisan optimize
* 自动加载优化 composer dumpautoload


#### 使用说明

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

2.为模型生成注释
```
    php artisan ide-helper:models
```

3.生成 PHPstorm Meta file
```
    php artisan ide-helper:meta
```

4.为 Facades 生产注释
```
php artisan ide-helper:generate
```

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request


#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  Gitee 官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解 Gitee 上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是 Gitee 最有价值开源项目，是综合评定出的优秀开源项目
5.  Gitee 官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  Gitee 封面人物是一档用来展示 Gitee 会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
