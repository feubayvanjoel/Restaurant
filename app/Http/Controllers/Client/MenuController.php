<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Boisson;
use Illuminate\Http\Request;

/**
 * Contrôleur du menu pour les clients
 * Affiche les plats et boissons disponibles
 */
class MenuController extends Controller
{
    /**
     * Afficher le menu complet
     */
    public function index()
    {
        // Récupérer tous les plats disponibles (quantité > 0)
        $plats = Plat::where('quantite', '>', 0)
            ->orderBy('nom')
            ->get();

        // Récupérer toutes les boissons disponibles (quantité > 0)
        $boissons = Boisson::where('quantite', '>', 0)
            ->orderBy('nom')
            ->get();

        // TODO: Récupérer le menu du jour (à implémenter avec une table dédiée)
        $menuDuJour = [];

        return view('client.menu.index', compact('plats', 'boissons', 'menuDuJour'));
    }

    /**
     * API : Récupérer les plats en JSON (pour actualisation AJAX)
     */
    public function getPlats()
    {
        $plats = Plat::where('quantite', '>', 0)
            ->orderBy('nom')
            ->get();

        return response()->json($plats);
    }

    /**
     * API : Récupérer les boissons en JSON (pour actualisation AJAX)
     */
    public function getBoissons()
    {
        $boissons = Boisson::where('quantite', '>', 0)
            ->orderBy('nom')
            ->get();

        return response()->json($boissons);
    }
}
