<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('notifies')) return;
        // 【按月分表】
        // 作为基础表，所有的分表生成按照词表的结构进行复制并重命名
        Schema::create('notifies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('notify_id')->unsigned()->comment('系统消息通知记录表');
            $table->integer('notify_type')->unsigned()->default(0)->comment('消息类型：0.系统消息；1.互动消息；');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->bigInteger('target_id')->unsigned()->default(0)->comment('目标Id(比如动态ID)');
            $table->integer('target_type')->unsigned()->default(0)->comment('目标类型：0.注册；1.动态；2.关注');
            $table->bigInteger('sender_id')->unsigned()->default(0)->comment('发送者Id');
            $table->integer('sender_type')->unsigned()->default(0)->comment('发送者类型：0.系统通知');
            $table->integer('dynamic_type')->unsigned()->default(0)->comment('动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除');
            $table->text('notify_content')->comment('内容');
            $table->boolean('is_read')->unsigned()->default(0)->comment('是否已读：0：否；1：是');
            $table->bigInteger('admin_id')->unsigned()->default(0)->comment('管理员Id');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['user_id']);
            $table->index(['is_read']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifies');
    }
}
