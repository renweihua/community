<?php
namespace App\Modules\Bbs\Database\factories;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_mobile' => $this->faker->unique()->numberBetween(13000000001, 19099999999),
            'user_name' => $this->faker->unique()->userName,
            'user_email' => $this->faker->unique()->email,
            'password' => bcrypt(123456),
        ];
    }
}

