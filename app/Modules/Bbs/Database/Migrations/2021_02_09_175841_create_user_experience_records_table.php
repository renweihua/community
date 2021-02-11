<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExperienceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_experience_records')) return;
        Schema::create('user_experience_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('experience_id')->unsigned()->comment('会员经验变动记录表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户的id');
            $table->smallInteger('experience_num')->unsigned()->default(0)->comment('获得多少经验');
            $table->boolean('get_type')->unsigned()->default(0)->comment('获得类型：
                0：会员登录【一次 + 1】；
                1.签到【累加】；
                2.支付【一次 + 5】；
                3.绑定手机号码【 + 10 】；
                4.验证邮箱【 + 10 】；
                5.实名认证【 + 20 】');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->string('description', 200)->default('')->comment('描述');
            $table->index(['created_time']);
            $table->index(['get_type']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_experience_records');
    }
}
