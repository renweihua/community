<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDouyinAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = env('DB_PREFIX') . 'douyin_authors';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned()->comment('Id');
            $table->string('sec_uid', 200)->default('')->comment('');
            $table->string('uid', 200)->default('')->comment('');
            $table->string('unique_id', 200)->default('')->comment('');
            $table->string('nick_name', 200)->default('')->comment('昵称');
            $table->string('avatar_thumb', 200)->default('')->comment('头像');
            $table->string('share_url', 200)->default('')->comment('抖音作者分享的URL');
            $table->bigInteger('total_favorited')->unsigned()->default(0)->comment('');
            $table->bigInteger('follower_count')->unsigned()->default(0)->comment('关注数量');
            $table->string('signature', 200)->default('')->comment('签名');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['is_delete']);
            $table->unique(['sec_uid']);
            $table->unique(['uid']);
            $table->unique(['unique_id']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . $table . "` comment '抖音作者表'");
        // 设置自增Id从 10000 开始
        DB::statement("ALERT TABLE {$table} AUTO_INCREMENT=10000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('douyin_authors');
    }
}
