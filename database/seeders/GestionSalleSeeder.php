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
            $tables[] = [
                'NUMERO' => $i,
                'STATUT' => 'Libre',
                'CAPACITE' => rand(2, 8)
            ];
        }
        \DB::table('gestion_salle')->insert($tables);
    }
}
