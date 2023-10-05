<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Breed;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $breed = Breed::inRandomOrder()->first();

        return [
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->paragraph,
            'price_ht' => $this->faker->randomFloat(2, 10, 100),
            'sale_status' => $this->faker->boolean,
            'breed_id' => $breed->id,
        ];
    }
}
