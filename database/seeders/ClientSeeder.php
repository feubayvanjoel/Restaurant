<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('client')->insert([
            [
                'nom' => 'Feuba',
                'prenom' => 'Yvan',
                'email' => 'feuba@email.com',
                'numero' => '+32 474 12 34 56',
                'adresse' => 'Rue de Rivoli, Paris',
            ],
            [
                'nom' => 'Ambassa',
                'prenom' => 'Joel',
                'email' => 'joel@email.com',
                'numero' => '+32 475 23 45 67',
                'adresse' => 'Rue de la Republique, Lyon',
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Pierre',
                'email' => 'durand@email.com',
                'numero' => '+32 476 34 56 78',
                'adresse' => 'Avenue de la Canebiere, Marseille',
            ],
            [
                'nom' => 'Leroy',
                'prenom' => 'Marie',
                'email' => 'leroy@email.com',
                'numero' => '+32 477 45 67 89',
                'adresse' => 'Promenade des Anglais, Nice',
            ],
            [
                'nom' => 'Ken',
                'prenom' => 'bray',
                'email' => 'ken@email.com',
                'numero' => '+32 478 56 78 90',
                'adresse' => 'Rue de la Dalbade, Toulouse',
            ],
        ]);
    }
}
