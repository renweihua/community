<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('friend_applies')) return;
        Schema::create('friend_applies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('apply_id')->unsigned()->comment('好友申请表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户Id');
            $table->bigInteger('friend_id')->unsigned()->default(0)->comment('好友Id');
            $table->json('message_record')->nullable()->comment('消息记录 - 申请原因，申请方与接收方可以多次进行回复');
            $table->boolean('is_check')->unsigned()->default(0)->comment('是否审核：1：同意；0：待处理；2.拒绝');
            $table->boolean('is_finish')->unsigned()->default(0)->comment('是否已结束：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['user_id']);
            $table->index(['is_check']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_applies');
    }
}
