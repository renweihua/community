<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('dynamic_comments')) return;
        Schema::create('dynamic_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('comment_id')->unsigned()->comment('动态评论回复表');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->integer('reply_user')->unsigned()->default(0)->comment('会员Id');
            $table->integer('dynamic_id')->unsigned()->default(0)->comment('动态Id');
            $table->bigInteger('author_id')->unsigned()->default(0)->comment('作者Id');
            $table->string('content_type', 10)->default(0)->comment('内容的格式：html；markdown');
            $table->mediumText('comment_content')->nullable()->comment('回复内容');
            $table->mediumText('comment_markdown')->nullable()->comment('回复内容');
            $table->integer('top_level')->unsigned()->default(0)->comment('归属评论Id');
            $table->integer('reply_id')->unsigned()->default(0)->comment('回复评论的Id');
            $table->integer('praise_count')->unsigned()->default(0)->comment('点赞量');
            $table->boolean('is_read')->unsigned()->default(0)->comment('是否已读：0：否；1：是');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['dynamic_id']);
            $table->index(['user_id']);
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
        Schema::dropIfExists('dynamic_comments');
    }
}
