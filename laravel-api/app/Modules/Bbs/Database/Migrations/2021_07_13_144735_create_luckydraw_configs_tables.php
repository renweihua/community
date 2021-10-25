<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawConfigsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraw_configs')) return;
        Schema::create('luckydraw_configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('detail_id')->unsigned()->comment('活动详情Id');
            $table->integer('activity_id')->unsigned()->default(0)->comment('活动Id');
            $table->string('reward_name', 200)->default('')->comment('几等奖');
            $table->boolean('reward_type')->default(0)->comment('奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等');
            $table->decimal('reward_quota', 12, 2)->default(0)->comment('奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】');
            $table->integer('product_id')->unsigned()->default(0)->comment('产品Id');
            $table->integer('awards_num')->unsigned()->default(0)->comment('该奖励的发奖次数');
            $table->decimal('probability_of_winning', 20, 10)->default(0)->comment('获奖的概率');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->index(['is_delete']);
            $table->index(['activity_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "luckydraw_configs` comment '抽奖活动转盘配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraw_configs');
    }
}
