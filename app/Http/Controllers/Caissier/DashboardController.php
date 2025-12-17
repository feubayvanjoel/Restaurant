<?php

namespace App\Http\Controllers\Caissier;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * Contrôleur du tableau de bord CAISSIER
 * Vue d'ensemble des encaissements
 */
class DashboardController extends Controller
{
    /**
     * Afficher le dashboard caissier
     */
    public function index()
    {
        // Commandes servies en attente de paiement
        $commandesAEncaisser = Commande::where('statut', 'Servie')
            ->with(['client', 'table', 'ticket'])
            ->orderBy('horaire', 'asc')
            ->get();

        // Encaissements du jour
        $encaissementsDuJour = Ticket::whereDate('dateTicket', today())
            ->with('commande.client')
            ->orderBy('dateTicket', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'a_encaisser' => $commandesAEncaisser->count(),
            'ca_jour' => Ticket::whereDate('dateTicket', today())->sum('prix'),
            'tickets_jour' => Ticket::whereDate('dateTicket', today())->count(),
            'ticket_moyen' => Ticket::whereDate('dateTicket', today())->avg('prix') ?? 0,
        ];

        return view('caissier.dashboard', compact(
            'commandesAEncaisser',
            'encaissementsDuJour',
            'stats'
        ));
    }
}
