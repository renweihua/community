<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDouyinVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'douyin_videos';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('video_id')->unsigned()->comment('Id');
            $table->bigInteger('author_id')->unsigned()->default(0)->comment('作者Id');
            $table->string('aweme_id', 300)->default('')->comment('视频Id');
            $table->string('cover', 300)->default('')->comment('封面图');
            $table->string('desc', 300)->default('')->comment('描述');
            $table->json('images')->nullable()->comment('多图');

            // path 视频地址
            // duration 时长
            // width 宽度
            // height 高度
            // ratio
            $table->json('video')->nullable()->comment('视频信息');

            $table->json('statistics')->nullable()->comment('视频统计信息');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['author_id']);
            $table->index(['is_delete']);
        });
        $table = get_db_prefix() . $table;
        // 设置表注释
        DB::statement("ALTER TABLE `{$table}` comment '抖音视频表'");
        // 设置自增Id从 10000 开始
        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT=10000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('douyin_videos');
    }
}
