<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOtherloginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_otherlogins')) return;
        Schema::create('user_otherlogins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户的id-会员基本信息表');
            $table->string('qq_info', 500)->default('')->comment('QQ登录的标识');
            $table->string('baidu_info', 500)->default('')->comment('百度登录的标识');
            $table->string('weibo_info', 500)->default('')->comment('微博登录的标识');
            $table->string('github_info', 500)->default('')->comment('github的标识');
            $table->string('weixin_info', 500)->default('')->comment('微信的标识');
            $table->boolean('user_origin')->unsigned()->default(0)->comment('来源：
                0：普通注册；
                1：QQ快捷登录；
                2：百度登录；
                3：微博登录；
                4：Github登录；
                5：小丑疯狂吧账户同步登录【已下线，暂不考虑】
                6：微信登录【不给个人，暂不考虑】
                ');
            $table->boolean('change_account')->unsigned()->default(0)->comment('是否允许更改账户：0.否；1.是【仅针对于第三方快捷登录的账户，仅可更改一次，值变动】');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_otherlogins');
    }
}
