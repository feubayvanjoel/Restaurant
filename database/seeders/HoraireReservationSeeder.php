<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoraireReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('horaire_reservation')->insert([
            [
                'idTable' => 10,
                'idClient' => 1,
                'statut' => 'TERNINEE',
                'echeance' => '2025-04-10 22:22:00',
                'nombre_personne' => 2,
                'date_debut' => '2025-04-10 20:00:00',
                'date_fin' => '2025-04-10 22:00:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 15,
                'idClient' => 2,
                'statut' => 'ACTIVE',
                'echeance' => '2025-04-15 19:00:00',
                'nombre_personne' => 4,
                'date_debut' => '2025-04-15 17:00:00',
                'date_fin' => '2025-04-15 19:00:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 18,
                'idClient' => 3,
                'statut' => 'ANNULEE',
                'echeance' => '2025-04-15 20:30:00',
                'nombre_personne' => 2,
                'date_debut' => '2025-04-15 18:30:00',
                'date_fin' => '2025-04-15 20:30:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 7,
                'idClient' => 4,
                'statut' => 'ACTIVE',
                'echeance' => '2025-04-15 21:00:00',
                'nombre_personne' => 3,
                'date_debut' => '2025-04-15 19:00:00',
                'date_fin' => '2025-04-15 21:00:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 19,
                'idClient' => 5,
                'statut' => 'ACTIVE',
                'echeance' => '2025-04-15 22:30:00',
                'nombre_personne' => 5,
                'date_debut' => '2025-04-15 20:30:00',
                'date_fin' => '2025-04-15 22:30:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 20,
                'idClient' => 5,
                'statut' => 'ACTIVE',
                'echeance' => '2025-04-16 22:30:00',
                'nombre_personne' => 2,
                'date_debut' => '2025-04-16 20:30:00',
                'date_fin' => '2025-04-16 22:30:00',
                'creer_le' => now(),
            ],
            [
                'idTable' => 3,
                'idClient' => 2,
                'statut' => 'ACTIVE',
                'echeance' => '2025-04-05 22:30:00',
                'nombre_personne' => 2,
                'date_debut' => '2025-04-05 20:30:00',
                'date_fin' => '2025-04-05 22:30:00',
                'creer_le' => now(),
            ],
        ]);
    }
}
