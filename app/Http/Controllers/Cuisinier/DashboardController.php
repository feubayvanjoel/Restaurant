<?php

namespace App\Http\Controllers\Cuisinier;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

/**
 * Contrôleur du tableau de bord CUISINIER
 * Affiche les commandes à préparer avec système Kanban
 */
class DashboardController extends Controller
{
    /**
     * Afficher le dashboard cuisinier (Kanban des commandes)
     */
    public function index()
    {
        // Commandes en attente de préparation
        $commandesEnAttente = Commande::where('statut', 'En attente')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        // Commandes en cours de préparation
        $commandesEnCours = Commande::where('statut', 'En cours')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        // Commandes prêtes (pour info)
        $commandesPrete = Commande::where('statut', 'Prete')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->limit(10)
            ->get();

        // Statistiques du jour
        $stats = [
            'total_jour' => Commande::whereDate('horaire', today())->count(),
            'en_attente' => $commandesEnAttente->count(),
            'en_cours' => $commandesEnCours->count(),
            'pretes' => Commande::where('statut', 'Prete')->whereDate('horaire', today())->count(),
        ];

        return view('cuisinier.dashboard', compact(
            'commandesEnAttente',
            'commandesEnCours',
            'commandesPrete',
            'stats'
        ));
    }
}
