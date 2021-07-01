<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('table_backups')) return;
        Schema::create('table_backups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('backup_id')->unsigned()->comment('数据库备份记录表');
            $table->integer('admin_id')->unsigned()->default(0)->comment('操作人');
            $table->string('created_ip', 20)->default('')->comment('创建IP');
            $table->string('tables_name', 2000)->default('')->comment('备份的表名');
            $table->integer('file_size')->unsigned()->default(0)->comment('文件大小：字节');
            $table->integer('file_num')->unsigned()->default(0)->comment('文件数量');
            $table->string('backup_files', 2000)->default('')->comment('备份的文件');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['is_delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_backups');
    }
}
