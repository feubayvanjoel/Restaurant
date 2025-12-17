<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComposerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('composer')->insert([
            ['IDPLATS' => 1, 'IDCOMMANDE' => 1, 'NBPLATS' => 2],
            ['IDPLATS' => 2, 'IDCOMMANDE' => 2, 'NBPLATS' => 1],
            ['IDPLATS' => 3, 'IDCOMMANDE' => 3, 'NBPLATS' => 3],
            ['IDPLATS' => 4, 'IDCOMMANDE' => 4, 'NBPLATS' => 1],
            ['IDPLATS' => 5, 'IDCOMMANDE' => 5, 'NBPLATS' => 2],
        ]);
    }
}
