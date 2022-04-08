<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('web_logs')) return;
        Schema::create('web_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('log_id')->unsigned()->comment('Id');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户的id');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 300)->default('')->comment('创建时浏览器类型');
            $table->boolean('log_status')->unsigned()->default(1)->comment('状态：1.成功；0.失败');
            $table->string('log_description', 200)->default('')->comment('描述');
            $table->string('this_url', 200)->default('')->comment('当前URL');
            $table->string('request_url', 200)->default('')->comment('请求URL');
            $table->decimal('log_duration', 20, 12)->default(0)->comment('请求时长');
            $table->string('log_action', 200)->default('')->comment('请求方法');
            $table->string('log_method', 20)->default('')->comment('请求类型/请求方式');
            $table->json('request_data')->nullable()->comment('请求参数');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：1：是；0：否');
            $table->index(['is_delete']);
            $table->index(['user_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "web_logs` comment 'API请求日志表'");
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
