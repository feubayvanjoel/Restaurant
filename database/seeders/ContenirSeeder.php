<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContenirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('contenir')->insert([
            ['IDBOISSONS' => 1, 'IDCOMMANDE' => 1, 'NBBOISSONS' => 3],
            ['IDBOISSONS' => 2, 'IDCOMMANDE' => 2, 'NBBOISSONS' => 2],
            ['IDBOISSONS' => 3, 'IDCOMMANDE' => 3, 'NBBOISSONS' => 4],
            ['IDBOISSONS' => 4, 'IDCOMMANDE' => 4, 'NBBOISSONS' => 1],
            ['IDBOISSONS' => 5, 'IDCOMMANDE' => 5, 'NBBOISSONS' => 2],
        ]);
    }
}
