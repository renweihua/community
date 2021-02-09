<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('friend_groups')) return;
        Schema::create('friend_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('notify_id')->unsigned()->comment('好友分组表');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->string('group_name', 100)->default('')->comment('名称');
            $table->integer('friend_nums')->unsigned()->default(0)->comment('好友数量');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            // 索引
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_groups');
    }
}
