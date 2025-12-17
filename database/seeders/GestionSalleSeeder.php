<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GestionSalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [];
        for ($i = 1; $i <= 20; $i++) {
            $statut = ($i == 2 || $i == 5) ? 'Occupee' : 'Libre';
            $tables[] = ['NUMERO' => $i, 'STATUT' => $statut];
        }
        \DB::table('gestion_salle')->insert($tables);
    }
}
