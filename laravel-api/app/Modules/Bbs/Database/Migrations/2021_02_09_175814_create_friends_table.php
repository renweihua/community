<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('friends')) return;
        Schema::create('friends', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('好友表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户Id');
            $table->bigInteger('friend_id')->unsigned()->default(0)->comment('好友Id');
            $table->string('friend_remark', 200)->default('')->comment('备注');
            $table->boolean('is_special')->unsigned()->default(0)->comment('是否特别关心：1：是；0：否');
            $table->boolean('is_blacklist')->unsigned()->default(0)->comment('是否拉黑：1：是；0：否');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['user_id']);
            $table->index(['is_special']);
            $table->index(['is_blacklist']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
