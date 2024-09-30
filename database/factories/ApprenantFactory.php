<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apprenant>
 */
class ApprenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'date_naissance' => $this->faker->date(),
            'sexe' => $this->faker->randomElement(['Masculin', 'Féminin']),
            'referentiel_id' => $this->faker->numberBetween(1, 10),
            'photo' => $this->faker->imageUrl(),
            'matricule' => $this->faker->unique()->word,
            'qr_code' => $this->faker->randomNumber(),
            'is_active' => $this->faker->randomElement(['OUI', 'NON']),
        ];
    }
}
