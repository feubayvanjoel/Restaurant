<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre important : d'abord les tables sans dépendances
        $this->call([
            PersonnelSeeder::class,
            GestionSalleSeeder::class,
            PlatsSeeder::class,
            BoissonsSeeder::class,
            ClientSeeder::class,
            CompteSeeder::class,
            HoraireReservationSeeder::class,
            CommandeSeeder::class,
            ComposerSeeder::class,
            ContenirSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
