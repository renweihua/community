<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'versions';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('version_id')->unsigned()->comment('版本Id');
            $table->string('version_name', 200)->default('')->comment('名称');
            $table->string('version_number', 200)->default('')->comment('版本号');
            $table->integer('publish_time')->unsigned()->default(0)->comment('版本的发布时间');
            $table->text('version_content')->nullable()->comment('内容');
            $table->integer('version_sort')->unsigned()->default(0)->comment('排序');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "{$table}` comment '版本表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versions');
    }
}
