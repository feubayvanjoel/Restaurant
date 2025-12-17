<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('commande')->insert([
            ['IDTABLE' => 1, 'IDCLIENT' => 1, 'STATUT' => 'Confirmee'],
            ['IDTABLE' => 9, 'IDCLIENT' => 4, 'STATUT' => 'Terminee'],
            ['IDTABLE' => 12, 'IDCLIENT' => 3, 'STATUT' => 'Annulee'],
            ['IDTABLE' => 6, 'IDCLIENT' => 2, 'STATUT' => 'Confirmee'],
            ['IDTABLE' => 4, 'IDCLIENT' => 4, 'STATUT' => 'Confirmee'],
            ['IDTABLE' => 8, 'IDCLIENT' => 5, 'STATUT' => 'Confirmee'],
            ['IDTABLE' => 10, 'IDCLIENT' => 5, 'STATUT' => 'Terminee'],
            ['IDTABLE' => 19, 'IDCLIENT' => 5, 'STATUT' => 'Confirmee'],
        ]);
    }
}
