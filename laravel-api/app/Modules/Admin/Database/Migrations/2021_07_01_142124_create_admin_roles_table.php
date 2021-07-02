<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_roles')) return;
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('role_id')->unsigned()->comment('角色Id');
            $table->string('role_name', 100)->default('')->comment('角色名称');
            $table->string('role_remarks', 200)->default('')->comment('备注');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否可用：1：可用；0：禁用');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['is_delete']);
            $table->index(['is_check']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_roles` comment '管理员角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_roles');
    }
}
