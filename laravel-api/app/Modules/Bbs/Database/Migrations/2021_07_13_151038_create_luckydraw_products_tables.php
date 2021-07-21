<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckydrawProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('luckydraw_products')) return;
        Schema::create('luckydraw_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('product_id')->unsigned()->comment('产品Id');
            $table->string('product_name', 200)->default('')->comment('产品名称');
            $table->string('product_cover', 200)->default('')->comment('产品封面图');
            $table->integer('product_stock')->unsigned()->default(0)->comment('产品库存');
            $table->integer('sales_count')->unsigned()->default(0)->comment('销量');
            $table->boolean('on_sale')->unsigned()->default(1)->comment('是否在售：0.否；1.是');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：0.否；1.是');
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "luckydraw_products` comment '抽奖活动的产品表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luckydraw_products');
    }
}
