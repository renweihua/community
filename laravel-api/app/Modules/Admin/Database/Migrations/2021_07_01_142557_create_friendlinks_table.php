<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFriendlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('friendlinks')) return;
        Schema::create('friendlinks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('link_id')->unsigned()->comment('友情链接Id');
            $table->string('link_name', 256)->default('')->comment('名称');
            $table->string('link_url', 256)->default('')->comment('链接URL');
            $table->string('link_cover', 256)->default('')->comment('链接图标');
            $table->integer('link_sort')->unsigned()->default(0)->comment('排序[从小到大]');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否可用：1：可用；0：禁用');
            $table->boolean('open_window')->unsigned()->default(1)->comment('是否打开新窗口：1：是；0：否');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['link_sort']);
            $table->index(['is_check']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "friendlinks` comment '友情链接表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendlinks');
    }
}
