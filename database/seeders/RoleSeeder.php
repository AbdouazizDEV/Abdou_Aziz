<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->Admin()->create();
        Role::factory()->Manager()->create();
        Role::factory()->Coach()->create();
        Role::factory()->CM()->create();
        Role::factory()->Apprenant()->create();
    }
}
