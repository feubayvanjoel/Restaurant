<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Les tickets seront créés automatiquement par les triggers lors de la création des commandes
        // Mais on peut aussi les créer manuellement ici pour les commandes existantes
        $commandes = \DB::table('commande')->pluck('IDCOMMANDE');
        foreach ($commandes as $idCommande) {
            \DB::table('ticket')->insert([
                'IDCOMMANDE' => $idCommande,
                'PRIX' => 0.00,
            ]);
        }
    }
}
