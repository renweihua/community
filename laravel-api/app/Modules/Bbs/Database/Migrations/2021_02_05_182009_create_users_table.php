<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) return;
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('user_id')->unsigned()->comment('会员登录表');
            $table->string('user_mobile', 15)->default('')->comment('手机号');
            $table->string('user_name', 200)->default('')->comment('用户名');
            $table->string('user_email', 200)->default('')->comment('邮箱');
            $table->string('password', 60)->default('')->comment('登录密码');
            $table->string('login_token', 100)->default('')->comment('登录Token');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否审核：1：正常；0：禁用；2.踢出登录，重新登录');
            $table->index(['is_check']);
            $table->index(['user_name']);
            $table->index(['user_email']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_roles` comment '会员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
