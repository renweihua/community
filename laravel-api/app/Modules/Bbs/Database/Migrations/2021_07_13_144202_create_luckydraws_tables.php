<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraws')) return;
        Schema::create('luckydraws', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('activity_id')->unsigned()->comment('活动Id');
            $table->string('activity_name', 200)->default('')->comment('活动名称');
            $table->smallInteger('turntable_config')->default(6)->comment('转盘配置对应的几个组【4、6、8、10】');
            $table->boolean('is_open')->unsigned()->default(1)->comment('是否开启该抽奖活动');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "luckydraws` comment '抽奖活动表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraws');
    }
}
