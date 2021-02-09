<?php
namespace App\Modules\Bbs\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Modules\Bbs\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_mobile' => $this->faker->phoneNumber,
            'user_name' => $this->faker->unique()->userName,
            'user_email' => $this->faker->unique()->email,
            'password' => bcrypt(123456),
        ];
    }
}

