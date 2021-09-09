<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminRoleWithMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_role_with_menus')) return;
        Schema::create('admin_role_with_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('with_id')->unsigned()->comment('关联Id');
            $table->integer('role_id')->unsigned()->default(0)->comment('角色Id');
            $table->integer('menu_id')->unsigned()->default(0)->comment('菜单Id');
            $table->index(['role_id']);
            $table->index(['menu_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "admin_role_with_menus` comment '角色权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_with_menus');
    }
}
