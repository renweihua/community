<?php
namespace App\Modules\Bbs\Database\factories;

use App\Models\Dynamic\Dynamic;
use Illuminate\Database\Eloquent\Factories\Factory;

class DynamicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dynamic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $img = cnpscy_config('site_web_logo');
        return [
            'user_id' => $this->faker->numberBetween(1, 100),
            'topic_id' => $this->faker->numberBetween(1, 10),
            'dynamic_title' => $this->faker->unique()->name,
            'dynamic_images' => rand(0, 1) == 1 ? $img : ($img . ',' . $img),
            'dynamic_content' => $this->faker->text,
            'is_check' => 1,
            'is_public' => $this->faker->numberBetween(0, 2),
            'access_password' => hash_make(123456),
            'dynamic_type' => $this->faker->numberBetween(0, 3),
            'cache_extends' => json_encode([
                'read_num' => $this->faker->numberBetween(0, 10000),
                'comment_count' => $this->faker->numberBetween(0, 10000),
                'praise_count' => $this->faker->numberBetween(0, 10000),
                'collection_count' => $this->faker->numberBetween(0, 10000),
            ]),
            'created_time' => time(),
            'updated_time' => time(),
        ];
    }
}

