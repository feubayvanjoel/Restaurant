<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('plats')->insert([
            ['NOM' => 'Taro', 'QUANTITE' => 50, 'PRIX' => 17.50],
            ['NOM' => 'Koki', 'QUANTITE' => 30, 'PRIX' => 15.30],
            ['NOM' => 'Salade Cesar', 'QUANTITE' => 20, 'PRIX' => 7.00],
            ['NOM' => 'Burger', 'QUANTITE' => 40, 'PRIX' => 11.00],
            ['NOM' => 'Sushi', 'QUANTITE' => 25, 'PRIX' => 14.00],
        ]);
    }
}
