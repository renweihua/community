<?php

namespace App\Modules\Bbs\Database\Seeders;

use App\Models\Dynamic\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Topic::factory()->count(10)->create();
    }
}
