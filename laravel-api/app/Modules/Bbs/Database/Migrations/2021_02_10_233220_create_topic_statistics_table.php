<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('topic_statistics')) return;
        Schema::create('topic_statistics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('话题 统计表');
            $table->bigInteger('topic_id')->unsigned()->default(0)->comment('话题Id');
            $table->integer('follow_num')->unsigned()->default(0)->comment('关注数量');
            $table->integer('dynamic_num')->unsigned()->default(0)->comment('动态数量【包含文章、视频、动态、提问】');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_statistics');
    }
}
