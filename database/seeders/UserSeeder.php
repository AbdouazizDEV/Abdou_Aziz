<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        //Role::factory()->count(5)->create();
        User::create([
            'nom' => 'Homenick',
            'prenom' => 'Mortimer',
            'adresse' => '5738 Wiegand Trace Suite 664, Wendyland, IL 63001-0065',
            'telephone' => '+1-831-472-9290',
            'role_id' => 3,
            'email' => 'yturcotte@example.org',
            'photo' => 'https://via.placeholder.com/640x480.png/0011aa?text=illo',
            'statut' => 'active',
            'password' => bcrypt('your_password'), // Assurez-vous d'utiliser bcrypt pour le mot de passe
        ]);
    }
}
