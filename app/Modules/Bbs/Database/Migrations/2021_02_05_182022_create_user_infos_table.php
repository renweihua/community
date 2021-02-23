<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_infos')) return;
        Schema::create('user_infos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('会员Id-会员基本信息表');
            $table->uuid('user_uuid')->default('')->comment('UUID');
            $table->string('pay_pass', 60)->default('')->comment('支付密码');
            $table->string('nick_name', 200)->default('')->comment('昵称');
            $table->string('user_avatar', 200)->default('')->comment('头像');
            $table->string('background_cover', 200)->default('')->comment('背景封面图');
            $table->boolean('user_sex')->unsigned()->default(0)->comment('性别：0：男；1：女；2.保密');
            $table->integer('user_birth')->unsigned()->default(0)->comment('出生年月日');
            $table->string('city_info', 200)->default('')->comment('城市信息：省份,城市');
            $table->integer('get_likes')->unsigned()->default(0)->comment('获赞数');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 200)->default('')->comment('创建时浏览器类型');
            $table->integer('user_grade')->unsigned()->default(0)->comment('用户等级');
            $table->integer('user_experience')->unsigned()->default(0)->comment('用户经验');
            $table->boolean('auth_status')->unsigned()->default(0)->comment('实名认证状态：0：否，1：是');
            $table->boolean('auth_mobile')->unsigned()->default(0)->comment('手机号验证状态：0：否，1：是');
            $table->boolean('auth_email')->unsigned()->default(0)->comment('邮箱验证状态：0：否，1：是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->integer('total_sign_days')->unsigned()->default(0)->comment('总共签到天数');
            $table->integer('year_sign_days')->unsigned()->default(0)->comment('今年总共签到天数');
            $table->integer('sign_days')->unsigned()->default(0)->comment('连续签到天数');
            $table->integer('last_sign_time')->unsigned()->default(0)->comment('上次签到时间');
            $table->integer('last_actived_time')->unsigned()->default(0)->comment('上次活跃时间');
            $table->integer('notification_count')->unsigned()->default(0)->comment('未读消息');
            $table->string('user_introduction', 500)->default('')->comment('个人介绍');
            $table->primary('user_id');
            $table->index(['user_grade']);
            $table->index(['auth_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
