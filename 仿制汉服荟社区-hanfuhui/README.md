![书影o流风回雪-晚来天欲雪_700x.jpg](https://pic.hanfugou.com/android/2019/11/3/83b49515c8de4deeb312183cb881292c.jpg_700x.jpg) 

## 原型完成程度：
* 动态、关注、发布列表显示
* 荟吧、摄影、视频、话题、文章、活动列表显示
* 评论、点赞、回复功能
* 个人信息、背景封面、个人头像、密码修改
* 关注、黑名单、排行榜、处罚公示、汉币兑换、签到 
* 视频播放、图片预览

（活动报名、发布日常等、IM聊天功能、商城购物。这些涉及他人数据敏感操作就不添加了。）

```text
uni_hanfuhui_mini
┌─api                   RestfulAPI接口服务目录 
├─components            复用组件目录 
├─pages                 业务页面文件存放的目录
├─static                存放应用引用静态资源的目录 
├─store                 全局vuex数据仓库文件目录 
├─style                 全局公共样式文件目录 
├─unpackage             存放编译生成的文件目录 
├─utils                 常用函数工具文件目录 
├─main.js               Vue初始化入口文件 
├─App.vue               应用配置，用来配置App全局样式以及监听 应用生命周期 
├─manifest.json         配置应用名称、appid、logo、版本等打包信息 
└─pages.json            配置页面路由、导航条、选项卡等页面类信息 
```

感谢[插件市场](https://ext.dcloud.net.cn/)，发布组件的作者    
[mescroll -支持uni-app的下拉刷新上拉加载组件](https://ext.dcloud.net.cn/plugin?id=343) v1.1.5（2019-07-25）    
[uParse修复版-html富文本加载](https://ext.dcloud.net.cn/plugin?id=364) v1.1.0（2019-06-30）    
[SwipeAction 滑动操作](https://ext.dcloud.net.cn/plugin?id=181) 1.0.0（2019-08-26）    
以上插件可能都已经更新，做出大量优化。   

示例应用 `HBuilderX 2.4.6.20191210` 版编译，以微信小程序为主，条件编译打包APP并调整H5。

* [更多说明-简书](https://www.jianshu.com/p/89b08e8e60e7) 
* [更多说明-掘金](https://juejin.im/post/5e01bd99f265da33b507451c) 

## 总结

虽然数据量大时会存在卡顿，开发APP端的建议使用 `nvue` 页面结构同 vue, 由 template、style、script 构成。即将推出的v3大幅度增强原生渲染APP。查看 [uni-app 跨端开发注意事项](https://uniapp.dcloud.io/matter) ，部分跨端使用[条件编译](https://uniapp.dcloud.io/platform)调整。

最后推荐看完 [uni-app](https://uniapp.dcloud.io/) 官网介绍。

**祝愿DCloud越做越好**