<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('dynamics')) return;
        Schema::create('dynamics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('dynamic_id')->unsigned()->comment('动态表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->bigInteger('topic_id')->unsigned()->default(0)->comment('话题/荟吧 Id');
            $table->string('dynamic_title', 200)->default('')->comment('标题');
            $table->string('dynamic_images', 500)->default('')->comment('多图');
            $table->string('video_path', 200)->default('')->comment('视频地址');
            $table->string('video_info', 200)->default('')->comment('视频信息（JSON）');
            $table->string('content_type', 10)->default('')->comment('内容的格式：html；markdown');
            $table->mediumText('dynamic_content')->nullable()->comment('动态内容');
            $table->mediumText('dynamic_markdown')->nullable()->comment('动态内容');
            $table->boolean('is_check')->unsigned()->default(0)->comment('是否审核：0：待审核；1：通过；2.拒绝');
            $table->boolean('is_public')->unsigned()->default(1)->comment('公开度：0.私密；1.完全公开；2.密码访问');
            $table->string('access_password', 60)->default('')->comment('对于公开度的“密码访问”设置的密码');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 200)->default('')->comment('创建时浏览器类型');
            $table->integer('dynamic_type')->unsigned()->default(0)->comment('动态类型：0.动态；1.文章；2.视频；3.相册');
            $table->integer('excellent_time')->unsigned()->default(0)->comment('精选标记时间');

            $table->json('cache_extends')->nullable()->comment('统计的扩展字段');
            // $table->integer('read_num')->unsigned()->default(0)->comment('阅读数量');
            // $table->integer('comment_count')->unsigned()->default(0)->comment('评论总量');
            // $table->integer('praise_count')->unsigned()->default(0)->comment('点赞量');
            // $table->integer('collection_count')->unsigned()->default(0)->comment('收藏量');
            $table->index(['is_check']);
            $table->index(['user_id']);
            $table->index(['is_public']);
            $table->index(['excellent_time']);
            $table->index(['is_delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamics');
    }
}
