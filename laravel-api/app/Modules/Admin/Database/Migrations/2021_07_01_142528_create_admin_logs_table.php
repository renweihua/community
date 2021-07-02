<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_logs')) return;
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('log_id')->unsigned()->comment('日志Id');
            $table->integer('admin_id')->unsigned()->default(0)->comment('管理员Id');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 200)->default('')->comment('创建时浏览器类型');
            $table->string('log_description', 200)->default('')->comment('描述');
            $table->string('log_action', 200)->default('')->comment('请求方法');
            $table->string('log_method', 10)->default('')->comment('请求类型/请求方式');
            $table->string('request_url', 200)->nullable()->comment('请求的URL');
            $table->string('request_data', 500)->nullable()->comment('请求参数');
            $table->boolean('log_status')->unsigned()->default(1)->comment('登录状态：1.成功；0.失败');
            $table->decimal('log_duration', 20, 12)->default(0)->comment('请求时长');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['is_delete']);
            $table->index(['log_status']);
            $table->index(['admin_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_logs` comment '管理员操作日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
}
