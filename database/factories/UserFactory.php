<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = Role::inRandomOrder()->first();
         if(!$role){
             throw new \Exception('Role not found');
         }
        return [
            'nom' => fake()->lastName,
            'prenom' => fake()->firstName,
            'adresse' => fake()->address,
            'telephone' => fake()->phoneNumber,
            'role_id' => $role->id,
            'email' => fake()->unique()->safeEmail,
            'photo' => fake()->imageUrl(),
            'statut' => 'active',
            'password' => bcrypt('password'), // bcrypt pour hasher le mot de passe
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
