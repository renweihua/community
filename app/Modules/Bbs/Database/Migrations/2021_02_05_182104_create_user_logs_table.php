<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_logs')) return;
        Schema::create('user_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('log_id')->unsigned()->comment('会员登录日志记录表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户的id');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->smallInteger('log_type')->default(0)->comment('日志类型【0.登陆；1.退出；2.签到；……】');
            $table->smallInteger('login_type')->default(0)->comment('登录类型【0.普通登录】');
            $table->boolean('is_public')->unsigned()->default(1)->comment('是否展示：1.展示；0.会员删除；2.管理员删除');
            $table->boolean('log_status')->unsigned()->default(1)->comment('状态：1.成功；0.失败');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->json('request_data')->nullable()->comment('请求参数');
            $table->string('description', 256)->default('')->comment('描述');
            $table->json('extend_json')->nullable()->comment('扩展信息');
            $table->index(['created_time']);
            $table->index(['user_id']);
            $table->index(['is_public']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
