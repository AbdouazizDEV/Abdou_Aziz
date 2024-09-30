<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'libelle' => $this->faker->word,
            'date_debut' => $this->faker->date(),
            'date_fin' => $this->faker->date(),
            'duree' => $this->faker->numberBetween(1, 12),
            'etat' => 'active',
            'photo_de_couverture' => $this->faker->imageUrl(),
        ];
    }
}
