<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_infos')) return;
        Schema::create('admin_infos', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('admin_id')->unsigned()->default(0)->comment('管理员Id');
            $table->integer('login_num')->unsigned()->default(0)->comment('登录次数');
            $table->string('created_ip', 20)->default('')->comment('创建时的IP');
            $table->string('browser_type', 300)->default('')->comment('创建时浏览器类型');
            $table->boolean('admin_type')->unsigned()->default(0)->comment('管理员类型：0：普通管理员，1：设置的超级管理员（不根据权限表走）');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->primary('admin_id');
            $table->index(['admin_type']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "admin_infos` comment '管理员信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_infos');
    }
}
