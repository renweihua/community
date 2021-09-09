<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawRewardsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraw_rewards')) return;
        Schema::create('luckydraw_rewards', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('reward_id')->unsigned()->comment('领奖记录Id');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->integer('log_id')->unsigned()->default(0)->comment('活动抽奖记录Id');
            $table->integer('product_id')->unsigned()->default(0)->comment('产品Id');
            $table->json('receive_info')->nullable()->comment('收货信息【JSON】');
            $table->json('express_info')->nullable()->comment('快递信息【JSON】');
            $table->integer('reward_quota')->unsigned()->default(0)->comment('获取商品的数量');
            $table->string('user_remarks', 200)->default('')->comment('会员备注');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->integer('delivery_time')->unsigned()->default(0)->comment('发货时间');
            $table->integer('collect_time')->unsigned()->default(0)->comment('收货时间');
            $table->boolean('reward_status')->unsigned()->default(2)->comment('订单状态：0.待确认；1.已确认/待支付；2.已支付/待发货；3.已发货/待收货；4.已完成；5.已取消；6.已关闭');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->index(['user_id']);
            $table->index(['is_delete']);
            $table->index(['product_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "luckydraw_rewards` comment '抽奖活动的领取中奖产品的记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraw_rewards');
    }
}
