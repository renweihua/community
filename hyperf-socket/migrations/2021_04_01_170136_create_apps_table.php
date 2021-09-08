<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('apps')) return;
        Schema::create('apps', function (Blueprint $table) {
            // 指定表存储引擎
            $table->engine = 'InnoDB';
            $table->comment('APP管理表');
            $table->bigIncrements('app_id')->comment('Id');
            $table->string('app_name', 100)->default('')->comment('名称');
            // key与秘钥自动生成
            $table->string('app_key', 100)->default('')->comment('key');
            $table->string('app_secret', 100)->default('')->comment('秘钥');
            $table->boolean('app_type')->unsigned()->default(0)->comment('APP类型：0.Web；1.Android；2.IOS');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：1：删除；0：正常');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            // 索引
            $table->index('app_type');
            $table->index('is_delete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
}
