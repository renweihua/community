<?php
namespace App\Modules\Bbs\Database\factories;

use App\Models\User\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $citys = [
            '山西,运城',
            '湖南,长沙',
            '湖北,武汉',
            '广东,深圳',
            '上海',
            '北京',
        ];
        return [
            'user_uuid' => $this->faker->uuid,
            'nick_name' => $this->faker->name,
            'user_avatar' => cnpscy_config('site_web_logo'),
            'city_info' => $citys[rand(0, count($citys) - 1)],
            'user_sex' => rand(0, 2),
            'user_grade' => rand(0, 10),
            'notification_count' => rand(0, 10000),
            'get_likes' => rand(0, 10000),
            'created_time' => time(),
            'updated_time' => time(),
        ];
    }
}

