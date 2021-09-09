<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicCommentPraisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('dynamic_comment_praises')) return;
        Schema::create('dynamic_comment_praises', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('动态点赞表');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->integer('dynamic_id')->unsigned()->default(0)->comment('动态Id');
            $table->integer('comment_id')->unsigned()->default(0)->comment('评论表');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 200)->default('')->comment('创建时浏览器类型');
            $table->index(['user_id']);
            $table->index(['dynamic_id']);
            $table->index(['comment_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "dynamic_comment_praises` comment '动态的评论点赞记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_comment_praises');
    }
}
