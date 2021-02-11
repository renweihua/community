<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_signs')) return;
        Schema::create('user_signs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('sign_id')->unsigned()->comment('会员签到记录表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户的id');
            $table->boolean('sign_type')->unsigned()->default(0)->comment('签到类型：0：会员签到；1：后台手动添加');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->string('description', 200)->default('')->comment('描述');
            $table->string('created_ip', 20)->default('')->comment('创建IP');
            $table->index(['created_time']);
            $table->index(['sign_type']);
            $table->index(['user_id']);
            $table->index(['is_delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_signs');
    }
}
