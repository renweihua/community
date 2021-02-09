<?php

namespace App\Modules\Bbs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BbsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
            UserTableSeeder::class,
            UserGradeTableSeeder::class,
        ]);

        // $this->call("OthersTableSeeder");
    }
}
