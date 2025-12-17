<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Personnel accounts
        DB::table('compte')->insert([
            [
                'login' => 'admin',
                'password' => Hash::make('password'),
                'idProprietaire' => 1, // Admin
                'role' => 'ADMIN',
                'creer_le' => now(),
            ],
            [
                'login' => 'chef',
                'password' => Hash::make('password'),
                'idProprietaire' => 2, // Chef
                'role' => 'CUISINIER',
                'creer_le' => now(),
            ],
            [
                'login' => 'serveur',
                'password' => Hash::make('password'),
                'idProprietaire' => 3, // Serveur
                'role' => 'SERVEUR',
                'creer_le' => now(),
            ],
            [
                'login' => 'caissier',
                'password' => Hash::make('password'),
                'idProprietaire' => 4, // Caissier
                'role' => 'CAISSIER',
                'creer_le' => now(),
            ],
        ]);

        // Client accounts
        DB::table('compte')->insert([
            [
                'login' => 'feuba',
                'password' => Hash::make('password'),
                'idProprietaire' => 1, // Feuba
                'role' => 'CLIENT',
                'creer_le' => now(),
            ],
            [
                'login' => 'joel',
                'password' => Hash::make('password'),
                'idProprietaire' => 2, // Ambassa
                'role' => 'CLIENT',
                'creer_le' => now(),
            ],
            [
                'login' => 'durand',
                'password' => Hash::make('password'),
                'idProprietaire' => 3, // Durand
                'role' => 'CLIENT',
                'creer_le' => now(),
            ],
            [
                'login' => 'leroy',
                'password' => Hash::make('password'),
                'idProprietaire' => 4, // Leroy
                'role' => 'CLIENT',
                'creer_le' => now(),
            ],
            [
                'login' => 'ken',
                'password' => Hash::make('password'),
                'idProprietaire' => 5, // Ken
                'role' => 'CLIENT',
                'creer_le' => now(),
            ],
        ]);
    }
}
