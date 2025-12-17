<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\HoraireReservation;
use Illuminate\Http\Request;

/**
 * Contrôleur du tableau de bord CLIENT
 * Affiche un résumé des commandes et réservations du client
 */
class DashboardController extends Controller
{
    /**
     * Afficher le dashboard du client
     */
    public function index()
    {
        // Récupérer le client connecté via son compte
        $compte = auth()->user();
        $client = Client::find($compte->idProprietaire);

        if (!$client) {
            abort(404, 'Client introuvable');
        }

        // Récupérer les commandes en cours (non terminées, non annulées, non supprimées)
        $commandesEnCours = $client->commandes()
            ->whereNotIn('statut', ['Terminee', 'Annulee', 'Supprimee'])
            ->with(['ticket', 'table'])
            ->orderBy('horaire', 'desc')
            ->get();

        // Récupérer les réservations actives
        $reservationsEnCours = $client->reservations()
            ->where('statut', 'ACTIVE')
            ->with('table')
            ->orderBy('date_debut', 'asc')
            ->get();

        // Récupérer l'historique des commandes (terminées)
        $historiqueCommandes = $client->commandes()
            ->where('statut', 'Terminee')
            ->with(['ticket', 'table'])
            ->orderBy('horaire', 'desc')
            ->limit(10)
            ->get();

        // Récupérer l'historique des réservations (terminées ou annulées)
        $historiqueReservations = $client->reservations()
            ->whereIn('statut', ['TERNINEE', 'ANNULEE'])
            ->with('table')
            ->orderBy('date_debut', 'desc')
            ->limit(10)
            ->get();

        return view('client.dashboard', compact(
            'client',
            'commandesEnCours',
            'reservationsEnCours',
            'historiqueCommandes',
            'historiqueReservations'
        ));
    }
}
