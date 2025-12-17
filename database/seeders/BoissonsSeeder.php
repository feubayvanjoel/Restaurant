<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoissonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('boissons')->insert([
            ['NOM' => 'Coca-Cola', 'QUANTITE' => 100, 'PRIX' => 13.00],
            ['NOM' => 'Sprite', 'QUANTITE' => 150, 'PRIX' => 12.40],
            ['NOM' => 'Eau Minerale', 'QUANTITE' => 200, 'PRIX' => 6.00],
            ['NOM' => 'Jus d\'Orange', 'QUANTITE' => 120, 'PRIX' => 9.00],
            ['NOM' => 'The Glace', 'QUANTITE' => 80, 'PRIX' => 12.70],
        ]);
    }
}
