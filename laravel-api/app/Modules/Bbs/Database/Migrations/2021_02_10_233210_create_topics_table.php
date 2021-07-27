<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('topics')) return;
        Schema::create('topics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('topic_id')->unsigned()->comment('话题表');
            $table->bigInteger('parent_id')->unsigned()->default(0)->comment('父级Id');
            $table->string('topic_name', 200)->default('')->comment('名称');
            $table->string('topic_description', 200)->default('')->comment('描述');
            $table->string('topic_cover', 200)->default('')->comment('封面');
            $table->smallInteger('topic_sort')->unsigned()->default(0)->comment('排序');
            $table->integer('dynamic_count')->unsigned()->default(0)->comment('动态数量');
            $table->integer('follow_count')->unsigned()->default(0)->comment('关注人数');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index(['is_delete']);
            $table->index(['parent_id']);
            $table->index(['topic_sort']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
