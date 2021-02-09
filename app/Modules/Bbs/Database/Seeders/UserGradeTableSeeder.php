<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Modules\Bbs\Entities\User\UserGrade;
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

        $list = factory(UserGrade::class)->times(10)->make();
        UserGrade::insert($list->toArray());
    }
}
