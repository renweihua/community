<?php
namespace App\Modules\Bbs\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserGradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Modules\Bbs\Entities\UserGrade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grade_name' => $this->faker->unique()->name,
            'grade_sort' => $this->faker->numberBetween(0, 99),
            'created_time' => time(),
            'updated_time' => time(),
        ];
    }
}

