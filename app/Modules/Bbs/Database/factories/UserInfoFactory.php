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
        return [
            'user_uuid' => $this->faker->uuid,
            'nick_name' => $this->faker->name,
            'user_avatar' => cnpscy_config('site_web_logo'),
            'user_sex' => rand(0, 2),
            'user_grade' => rand(0, 10),
            'notification_count' => rand(0, 10000),
            'get_likes' => rand(0, 10000),
            'created_time' => time(),
            'updated_time' => time(),
        ];
    }
}

