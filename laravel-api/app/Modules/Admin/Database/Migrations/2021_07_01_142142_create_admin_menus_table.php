<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('admin_menus')) return;
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('menu_id')->unsigned()->comment('菜单Id');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级Id');
            $table->string('menu_name', 200)->default('')->comment('栏目名称');
            $table->string('vue_name', 200)->default('')->comment('Vue名称');
            $table->string('vue_path', 200)->default('')->comment('VUE路由路径');
            $table->string('vue_redirect', 200)->default('')->comment('Vue的redirect');
            $table->string('vue_icon', 30)->default('')->comment('图标');
            $table->string('vue_component', 200)->default('')->comment('VUE文件路径');
            $table->string('vue_meta', 200)->default('')->comment('');
            $table->string('external_links', 200)->default('')->comment('外链');
            $table->string('api_url', 200)->default('')->comment('接口路由');
            $table->string('api_method', 10)->default('')->comment('接口的请求方式');
            $table->integer('menu_sort')->unsigned()->default(99)->comment('排序[由小到大]');
            $table->tinyInteger('is_hidden')->unsigned()->default(0)->comment('是否隐藏菜单栏：1：是；0：否');
            $table->boolean('is_check')->unsigned()->default(1)->comment('是否可用：1：可用；0：禁用');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除：1：删除；0：否');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['menu_sort']);
            $table->index(['parent_id']);
            $table->index(['is_check']);
            $table->index(['is_delete']);
        });
        // 设置表注释
        DB::statement("ALTER TABLE `" . env('DB_PREFIX') . "admin_menus` comment '后台菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menus');
    }
}
