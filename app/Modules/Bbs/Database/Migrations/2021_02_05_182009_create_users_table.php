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
            $table->string('login_token', 100)->default('')->comment('login_token【用于提示是否异地登录，jwt-token只能检测是否登录---这个效果其实用不用都可以的，需要看心情。】');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否审核：1：正常；0：禁用；2.踢出登录，重新登录');
            $table->index(['is_check']);
        });
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
