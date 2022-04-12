<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_login_logs')) return;
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('log_id')->unsigned()->comment('会员登录日志记录表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 300)->default('')->comment('创建时浏览器类型');
            $table->string('description', 200)->default('')->comment('描述');
            $table->decimal('log_duration', 20, 12)->default(0)->comment('请求时长');
            $table->boolean('is_public')->unsigned()->default(1)->comment('是否展示：1.展示；0.会员删除；2.管理员删除');
            $table->boolean('log_status')->unsigned()->default(1)->comment('状态：1.成功；0.失败');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：1：是；0：否');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->json('request_data')->nullable()->comment('请求参数');
            $table->index(['is_delete']);
            $table->index(['user_id']);
            $table->index(['is_public']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_roles` comment '会员登录日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_logs');
    }
}
