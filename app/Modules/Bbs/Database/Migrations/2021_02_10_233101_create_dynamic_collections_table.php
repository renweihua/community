<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('dynamic_collections')) return;
        Schema::create('dynamic_collections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('relation_id')->unsigned()->comment('动态收藏表');
            $table->integer('user_id')->unsigned()->default(0)->comment('会员Id');
            $table->integer('dynamic_id')->unsigned()->default(0)->comment('动态Id-收藏表');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->bigInteger('created_ip')->default(0)->comment('创建时的IP-ip2long转换');
            $table->string('browser_type', 200)->default('')->comment('创建时浏览器类型');
            $table->index(['user_id']);
            $table->index(['dynamic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_collections');
    }
}
