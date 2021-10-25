<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('banners')) return;
        Schema::create('banners', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('banner_id')->unsigned()->comment('banner的Id');
            $table->string('banner_title', 200)->default('')->comment('标题');
            $table->string('banner_cover', 200)->default('')->comment('封面');
            $table->string('banner_link', 200)->default('')->comment('外链');
            $table->string('banner_words', 200)->default('')->comment('文字描述');
            $table->smallInteger('banner_sort')->unsigned()->default(0)->comment('排序[从小到大]');
            $table->boolean('is_check')->unsigned()->default(1)->comment('公开度：0.禁用；1.公开');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['banner_sort']);
            $table->index(['is_check']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . get_db_prefix() . "banners` comment 'Banner表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
