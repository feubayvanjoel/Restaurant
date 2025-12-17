<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personnel')->insert([
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'poste' => 'ADMIN',
                'email' => 'admin@restaurant.com',
                'numero' => '+32 470 12 34 56',
                'adresse' => 'Server Room'
            ],
            [
                'nom' => 'Chef',
                'prenom' => 'Cuisinier',
                'poste' => 'CUISINIER',
                'email' => 'chef@restaurant.com',
                'numero' => '+32 471 23 45 67',
                'adresse' => 'Kitchen'
            ],
            [
                'nom' => 'Serveur',
                'prenom' => 'Jean',
                'poste' => 'SERVEUR',
                'email' => 'serveur@restaurant.com',
                'numero' => '+32 472 34 56 78',
                'adresse' => 'Dining Hall'
            ],
            [
                'nom' => 'Caissier',
                'prenom' => 'Paul',
                'poste' => 'CAISSIER',
                'email' => 'caissier@restaurant.com',
                'numero' => '+32 473 45 67 89',
                'adresse' => 'Front Desk'
            ],
        ]);
    }
}
