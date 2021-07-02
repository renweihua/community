<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\User\UserGrade;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserGradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        UserGrade::factory()->count(10)->create();
    }
}
