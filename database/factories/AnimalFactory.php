<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Breed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    private const BREED_IMAGE = [
        1 => '/animalsPictures/base_labrador.jpg',
        2 => '/animalsPictures/base_frison.jpg',
        3 => '/animalsPictures/base_pottok.jpg',
        4 => '/animalsPictures/base_irish_cob.jpg',
        5 => '/animalsPictures/base_merinos.jpg',
        6 => '/animalsPictures/base_solognote.jpg',
    ];

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
            'sale_status' => $this->faker->boolean(65),
            'breed_id' => $breed->id,
            'image_path' => self::BREED_IMAGE[$breed->id],
            'user_id' => 1,
            'created_at' => $this->faker->dateTimeBetween('-1 month')
        ];
    }
}
