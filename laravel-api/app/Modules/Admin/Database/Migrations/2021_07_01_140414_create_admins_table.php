<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admins')) return;
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('admin_id')->unsigned()->comment('管理员Id');
            $table->string('admin_name', 100)->default('')->comment('管理员账户');
            $table->string('password', 60)->default('')->comment('登录密码');
            $table->string('admin_head', 200)->default('')->comment('头像');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否审核：1：正常；0：待审核；2.禁用');
            $table->boolean('kick_out')->unsigned()->default(2)->comment('是否踢出登录：0：表示在线；1：踢出登录；2.未登录');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->index(['is_check']);
            $table->index(['kick_out']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admins` comment '管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
