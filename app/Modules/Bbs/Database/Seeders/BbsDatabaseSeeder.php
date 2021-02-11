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
        // 会员相关的数据填充
        $this->call([
            UserTableSeeder::class,
            UserGradeTableSeeder::class,
        ]);

        // 动态话题相关的数据填充
        $this->call([
            TopicTableSeeder::class,
            DynamicTableSeeder::class,
        ]);
    }
}
