<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawQualificationSourcesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraw_qualification_sources')) return;
        Schema::create('luckydraw_qualification_sources', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned()->comment('');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->boolean('source_type')->unsigned()->default(0)->comment('抽奖机会的来源：1.签到；');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('change_type')->unsigned()->default(1)->comment('变更类型：0.减少；1.增加');
            $table->integer('luckydraw_times')->unsigned()->default(0)->comment('抽奖的次数【获取的次数】');
            $table->json('express_info')->comment('扩展信息【JSON】');
            $table->index(['user_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "luckydraw_qualification_sources` comment '抽奖活动的资格来源表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraw_qualification_sources');
    }
}
