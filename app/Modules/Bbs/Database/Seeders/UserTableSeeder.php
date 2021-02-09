<?php

namespace App\Modules\Bbs\Database\Seeders;

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

        factory(User::class, 100)->create()->each(function ($u) {
            $u->user_info()->save(factory(UserInfo::class)->make());
            $u->user_otherlogin()->save(factory(UserOtherlogin::class)->make());
        });
        // $this->call("OthersTableSeeder");
    }
}
