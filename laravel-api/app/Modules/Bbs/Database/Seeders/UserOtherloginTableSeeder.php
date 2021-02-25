<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\User\UserOtherlogin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserOtherloginTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        UserOtherlogin::factory()->times(100)->create();
    }
}
