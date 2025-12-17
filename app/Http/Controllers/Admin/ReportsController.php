<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\HoraireReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur des rapports et statistiques pour l'ADMIN
 */
class ReportsController extends Controller
{
    /**
     * Afficher la page des rapports
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', '7days');

        // Calculer les dates selon la période
        $dateDebut = match($periode) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '3months' => now()->subMonths(3),
            'year' => now()->subYear(),
            default => now()->subDays(7),
        };

        // Chiffre d'affaires
        $ca = [
            'total' => Ticket::where('dateTicket', '>=', $dateDebut)->sum('prix'),
            'moyenne' => Ticket::where('dateTicket', '>=', $dateDebut)->avg('prix'),
            'nombre' => Ticket::where('dateTicket', '>=', $dateDebut)->count(),
        ];

        // Commandes
        $commandes = [
            'total' => Commande::where('horaire', '>=', $dateDebut)->count(),
            'en_attente' => Commande::where('statut', 'En attente')->count(),
            'terminees' => Commande::where('horaire', '>=', $dateDebut)
                ->where('statut', 'Terminee')->count(),
        ];

        // Réservations
        $reservations = [
            'total' => HoraireReservation::where('creer_le', '>=', $dateDebut)->count(),
            'actives' => HoraireReservation::where('statut', 'ACTIVE')->count(),
        ];

        // Top clients
        $topClients = Client::withCount(['commandes' => function($q) use ($dateDebut) {
                $q->where('horaire', '>=', $dateDebut);
            }])
            ->having('commandes_count', '>', 0)
            ->orderBy('commandes_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'ca',
            'commandes',
            'reservations',
            'topClients',
            'periode'
        ));
    }
}
