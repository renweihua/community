<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowFansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_follow_fans')) return;
        Schema::create('user_follow_fans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('会员关注与粉丝记录表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('会员主键');
            $table->bigInteger('friend_id')->unsigned()->default(0)->comment('对应会员Id');
            // 两条对应记录同时更新
            $table->boolean('cross_correlation')->unsigned()->default(0)->comment('是否双方进行关注：0：否；1：是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->index(['user_id']);
            $table->index(['cross_correlation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follow_fans');
    }
}
