<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUploadGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('upload_groups')) return;
        Schema::create('upload_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('group_id')->unsigned()->comment('分组Id');
            $table->string('group_name', 200)->default('')->comment('分组名称');
            $table->integer('group_sort')->unsigned()->default(0)->comment('排序[从小到大]');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['group_sort']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "upload_groups` comment '文件分组表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_groups');
    }
}
