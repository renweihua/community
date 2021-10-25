#### 使用说明

###### 启动服务，且热更新
* 命令行：`php bin/hyperf.php server:watch`
* www角色执行命令：`su -c "php bin/hyperf.php serve:watch" -s /bin/sh www`
* 后置进程：`nohup php 文件 2>&1 &`
* 使用`supervisor`守护进程启动服务即可