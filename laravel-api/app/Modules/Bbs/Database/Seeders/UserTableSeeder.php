<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\System\Notify;
use App\Models\User\User;
use App\Models\User\UserInfo;
use App\Models\User\UserOtherlogin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        User::factory()->times(100)->create()->each(function ($u) {
            $u->userInfo()->save(UserInfo::factory()->make());
            $u->userOtherlogin()->save(UserOtherlogin::factory()->make());


            // 注册成功：站内系统消息发送
            Notify::insert([
                'notify_type' => Notify::NOTIFY_TYPE['SYSTEM_MSG'],
                'user_id' => $u->user_id,
                'target_type' => Notify::TARGET_TYPE['REGISTER'],
                'sender_type' => Notify::SYSTEM_SENDER,
                'notify_content' => '注册成功，请完善个人资料！',
            ]);
        });
    }
}
