<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_grades')) return;
        Schema::create('user_grades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('grade_id')->unsigned()->comment('会员等级表【禁止删除，允许修改】');
            $table->string('grade_name', 200)->default('')->comment('等级名称');
            $table->string('min_value', 200)->default('')->comment('最小经验值');
            $table->string('max_value', 200)->default('')->comment('最大经验值');
            $table->integer('grade_sort')->unsigned()->default(0)->comment('排序');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->index(['created_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_grades');
    }
}
