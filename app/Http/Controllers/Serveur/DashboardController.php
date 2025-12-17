<?php

namespace App\Http\Controllers\Serveur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

/**
 * Contrôleur du tableau de bord SERVEUR
 * Vue d'ensemble des tables et commandes à servir
 */
class DashboardController extends Controller
{
    /**
     * Afficher le dashboard serveur
     */
    public function index()
    {
        // Toutes les tables avec leur statut
        $tables = RestaurantTable::orderBy('numero')->get();

        // Commandes prêtes à servir
        $commandesPrete = Commande::where('statut', 'Prete')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        // Commandes en cours de service
        $commandesEnCours = Commande::where('statut', 'En cours')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        // Statistiques
        $stats = [
            'tables_libres' => RestaurantTable::where('statut', 'Libre')->count(),
            'tables_occupees' => RestaurantTable::where('statut', 'Occupee')->count(),
            'commandes_prete' => $commandesPrete->count(),
            'commandes_jour' => Commande::whereDate('horaire', today())->count(),
        ];

        return view('serveur.dashboard', compact(
            'tables',
            'commandesPrete',
            'commandesEnCours',
            'stats'
        ));
    }
}
