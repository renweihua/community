<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\User\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        UserInfo::factory()->times(100)->create();
    }
}
