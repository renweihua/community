<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailVerifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_email_verifies')) return;
        Schema::create('user_email_verifies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('verify_id')->unsigned()->comment('邮箱验证表');
            $table->bigInteger('user_id')->unsigned()->default('0')->comment('会员Id');
            $table->string('user_email', 100)->default('')->comment('邮箱');
            $table->string('verify_token', 256)->default('')->comment('验证TOKEN');
            $table->boolean('auth_email')->unsigned()->default(0)->comment('邮箱验证状态：0：否，1：是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['user_id']);
            $table->index(['auth_email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_email_verifies');
    }
}
