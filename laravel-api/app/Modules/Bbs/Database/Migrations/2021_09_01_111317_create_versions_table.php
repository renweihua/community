<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('versions')) return;
        Schema::create('versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('version_id')->unsigned()->comment('版本Id');
            $table->boolean('version_type')->unsigned()->default(0)->comment('版本类型：1.android；2.ios');
            $table->string('version_name', 200)->default('')->comment('版本名称');
            $table->string('version_number', 200)->default('')->comment('版本号');
			$table->integer('version_code')->default(100)->comment('纯数字的版本号');
            $table->string('version_desc', 200)->default('')->comment('描述');
            $table->string('apk_url', 200)->default('')->comment('');
            $table->integer('publish_time')->unsigned()->default(0)->comment('版本的发布时间');
            $table->boolean('is_update')->unsigned()->default(0)->comment('0.不强制更新；1.强制更新');
            $table->boolean('update_type')->unsigned()->default(0)->comment('0.热更新；1.整包更新');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['version_type']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "versions` comment '版本表'");
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
