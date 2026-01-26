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
        $commandes = [
            ['IDTABLE' => 1, 'IDCLIENT' => 1, 'STATUT' => 'Confirmee', 'NB_PERSONNES' => 2, 'HORAIRE' => now()->subMinutes(15)],
            ['IDTABLE' => 9, 'IDCLIENT' => 4, 'STATUT' => 'Terminee', 'NB_PERSONNES' => 4, 'HORAIRE' => now()->subHours(2)],
            ['IDTABLE' => 12, 'IDCLIENT' => 3, 'STATUT' => 'Annulee', 'NB_PERSONNES' => 2, 'HORAIRE' => now()->subHours(1)],
            ['IDTABLE' => 6, 'IDCLIENT' => 2, 'STATUT' => 'Confirmee', 'NB_PERSONNES' => 3, 'HORAIRE' => now()->subMinutes(30)],
            ['IDTABLE' => 4, 'IDCLIENT' => 4, 'STATUT' => 'Préparée', 'NB_PERSONNES' => 4, 'HORAIRE' => now()->subMinutes(45)], // En cuisine
            ['IDTABLE' => 8, 'IDCLIENT' => 5, 'STATUT' => 'Servie', 'NB_PERSONNES' => 5, 'HORAIRE' => now()->subMinutes(90)], // Mange
            ['IDTABLE' => 10, 'IDCLIENT' => 5, 'STATUT' => 'Terminee', 'NB_PERSONNES' => 2, 'HORAIRE' => now()->subHours(3)],
            ['IDTABLE' => 19, 'IDCLIENT' => 5, 'STATUT' => 'En préparation', 'NB_PERSONNES' => 4, 'HORAIRE' => now()->subMinutes(10)],
        ];

        \DB::table('commande')->insert($commandes);

        // Update tables to 'Occupee' for active orders
        $activeStatuses = ['Confirmee', 'En préparation', 'Préparée', 'Servie', 'En attente'];
        
        $occupiedTableIds = collect($commandes)
            ->whereIn('STATUT', $activeStatuses)
            ->pluck('IDTABLE')
            ->unique();

        \DB::table('gestion_salle')
            ->whereIn('IDTABLE', $occupiedTableIds)
            ->update(['STATUT' => 'Occupee']);
    }
}
