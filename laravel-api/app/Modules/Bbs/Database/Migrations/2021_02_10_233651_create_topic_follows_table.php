<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('topic_follows')) return;
        Schema::create('topic_follows', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('话题关注表');
            $table->bigInteger('user_id')->unsigned()->default('0')->comment('会员主键');
            $table->bigInteger('topic_id')->unsigned()->default(0)->comment('话题Id');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->index('user_id');
            $table->index('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_follows');
    }
}
