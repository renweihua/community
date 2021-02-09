<?php

namespace App\Modules\Bbs\Database\Seeders;

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
        });
    }
}
