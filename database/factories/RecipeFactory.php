<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'ingredients' => fake()->text(),
            'instructions' => fake()->text(),
            'image' => 'recipes/'.fake()->image(storage_path('app/public/recipes')),
            'user_id' => User::factory(),
        ];
    }
}
