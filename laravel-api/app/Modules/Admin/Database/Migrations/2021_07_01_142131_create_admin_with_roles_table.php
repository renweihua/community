<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminWithRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_with_roles')) return;
        Schema::create('admin_with_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('with_id')->unsigned()->comment('关联Id');
            $table->integer('role_id')->unsigned()->default(0)->comment('角色Id');
            $table->integer('admin_id')->unsigned()->default(0)->comment('管理员Id');
            $table->index(['role_id']);
            $table->index(['admin_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_with_roles` comment '管理员关联角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_with_roles');
    }
}
