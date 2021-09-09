<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawLogsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraw_logs')) return;
        Schema::create('luckydraw_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('log_id')->unsigned()->comment('活动抽奖记录Id');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->integer('activity_id')->unsigned()->default(0)->comment('活动Id');
            $table->integer('detail_id')->unsigned()->default(0)->comment('活动配置Id');
            $table->boolean('reward_type')->default(0)->comment('奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等');
            $table->decimal('reward_quota', 12, 2)->default(0)->comment('奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】');
            $table->integer('product_id')->unsigned()->default(0)->comment('产品Id');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_receive')->unsigned()->default(0)->comment('是否已领取：0.否；1.是');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->index(['user_id']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "luckydraw_logs` comment '抽奖活动的会员抽奖记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraw_logs');
    }
}
