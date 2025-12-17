<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Personnel;
use App\Models\Commande;
use App\Models\HoraireReservation;
use App\Models\Ticket;
use App\Models\Plat;
use App\Models\Boisson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur du tableau de bord ADMIN
 * Affiche les statistiques globales et l'activité récente
 */
class DashboardController extends Controller
{
    /**
     * Afficher le dashboard administrateur
     */
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_clients' => Client::count(),
            'total_personnel' => Personnel::count(),
            'total_commandes' => Commande::count(),
            'commandes_jour' => Commande::whereDate('horaire', today())->count(),
            'reservations_actives' => HoraireReservation::where('statut', 'ACTIVE')->count(),
            'chiffre_affaires_total' => Ticket::sum('prix'),
            'chiffre_affaires_jour' => Ticket::whereDate('dateTicket', today())->sum('prix'),
            'plats_stock_faible' => Plat::where('quantite', '<', 10)->count(),
            'boissons_stock_faible' => Boisson::where('quantite', '<', 10)->count(),
        ];

        // Activité récente - dernières commandes
        $dernieresCommandes = Commande::with(['client', 'ticket', 'table'])
            ->orderBy('horaire', 'desc')
            ->limit(10)
            ->get();

        // Activité récente - dernières réservations
        $dernieresReservations = HoraireReservation::with(['client', 'table'])
            ->orderBy('creer_le', 'desc')
            ->limit(10)
            ->get();

        // Produits en rupture ou faible stock
        $alertesStock = [
            'plats' => Plat::where('quantite', '<', 10)->get(),
            'boissons' => Boisson::where('quantite', '<', 10)->get(),
        ];

        // Statistiques par période (7 derniers jours)
        $statsParJour = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $statsParJour[] = [
                'date' => $date->format('d/m'),
                'commandes' => Commande::whereDate('horaire', $date)->count(),
                'ca' => Ticket::whereDate('dateTicket', $date)->sum('prix'),
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'dernieresCommandes',
            'dernieresReservations',
           'alertesStock',
            'statsParJour'
        ));
    }
}
