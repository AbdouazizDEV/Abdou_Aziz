<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ReferentielSeeder::class,
            PromotionSeeder::class,
        ]);
        $this->call(ApprenantsSeeder::class);
        $this->call(ArtisansSeeder::class);
        $this->call(CompetencesSeeder::class);
        $this->call(ModulesSeeder::class);
        $this->call(NotesSeeder::class);
        $this->call(ArchivesSeeder::class);
    }
}
