<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'libelle' => $this->faker->randomElement(['Admin', 'Manager', 'Coach', 'CM', 'Apprenant']),
        ];
    }

    public function Admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'libelle' => 'Admin',
            ];
        });
    }
    public function Manager()
    {
        return $this->state(function (array $attributes) {
            return [
                'libelle' => 'Manager',
            ];
        });
    }
    public function Coach()
    {
        return $this->state(function (array $attributes) {
            return [
                'libelle' => 'Coach',
            ];
        });
    }
    public function CM()
    {
        return $this->state(function (array $attributes) {
            return [
                'libelle' => 'CM',
            ];
        });
    }   
    public function Apprenant()
    {
        return $this->state(function (array $attributes) {
            return [
                'libelle' => 'Apprenant',
            ];
        });
    }
}
