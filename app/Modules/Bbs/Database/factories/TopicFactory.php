<?php
namespace App\Modules\Bbs\Database\factories;

use App\Models\Dynamic\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'topic_name' => $this->faker->name,
            'topic_description' => $this->faker->text,
            'topic_sort' => $this->faker->numberBetween(1, 99),
            'created_time' => time(),
            'updated_time' => time(),
        ];
    }
}

