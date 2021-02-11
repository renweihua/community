<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\Dynamic\Dynamic;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DynamicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Dynamic::factory()->count(1000)->create();
    }
}
