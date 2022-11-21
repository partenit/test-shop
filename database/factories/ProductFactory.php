<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'slug' => $this->faker->unique()->slug,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => $this->faker->numberBetween(1, 10),
            'code' => Str::random(10),
        ];
    }
}
