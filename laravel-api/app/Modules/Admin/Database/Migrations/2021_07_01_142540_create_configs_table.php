<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('configs')) return;
        Schema::create('configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('config_id')->unsigned()->comment('配置Id');
            $table->string('config_title', 256)->default('')->comment('标题');
            $table->string('config_name', 256)->default('')->comment('参数名称');
            $table->string('config_value', 500)->default('')->comment('参数值');
            $table->smallInteger('config_group')->default(0)->comment('分组');
            $table->string('config_extra', 256)->default('')->comment('配置项');
            $table->smallInteger('config_type')->unsigned()->default(0)->comment('类型：0.字符串；1.数字；2.文本；3.select下拉框；4.图片；5.富文本');
            $table->smallInteger('config_sort')->unsigned()->default(0)->comment('排序[从小到大]');
            $table->string('config_remark', 256)->default('')->comment('说明');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否审核/可用');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index(['created_time']);
            $table->index(['config_type']);
            $table->index(['config_sort']);
            $table->index(['is_check']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "configs` comment '系统配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
