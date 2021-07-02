<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUploadFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('upload_files')) return;
        Schema::create('upload_files', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('file_id')->unsigned()->comment('文件Id');
            $table->integer('group_id')->unsigned()->default(0)->comment('分组Id');
            $table->string('storage', 20)->default('')->comment('存储方式');
            $table->string('host_url', 256)->default('')->comment('存储域名');
            $table->string('file_name', 256)->default('')->comment('文件路径');
            $table->integer('file_size')->unsigned()->default(0)->comment('文件大小(字节)');
            $table->string('file_type', 256)->default('')->comment('文件类型');
            $table->string('extension', 256)->default('')->comment('文件扩展名');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['group_id']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "upload_files` comment '文件表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
    }
}
