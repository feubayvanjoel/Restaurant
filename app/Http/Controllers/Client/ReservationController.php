<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\HoraireReservation;
use App\Models\GestionSalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur de gestion des réservations pour les clients
 * Permet de créer, visualiser et gérer les réservations de tables
 */
class ReservationController extends Controller
{
    /**
     * Afficher la liste des réservations du client
     */
    public function index()
    {
        $compte = auth()->user();
        $client = Client::find($compte->idProprietaire);

        $reservations = $client->reservations()
            ->where('statut', '!=', 'SUPPRIMEE')
            ->with('table')
            ->orderBy('date_debut', 'desc')
            ->get();

        return view('client.reservations.index', compact('reservations'));
    }

    /**
     * Afficher le formulaire de création de réservation
     */
    public function create()
    {
        // Récupérer toutes les tables pour afficher leur disponibilité
        $tables = GestionSalle::orderBy('numero')->get();

        return view('client.reservations.create', compact('tables'));
    }

    /**
     * Vérifier la disponibilité d'une table (API JSON)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'idTable' => ['required', 'exists:gestion_salle,idTable'],
            'date_debut' => ['required', 'date', 'after:now'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
        ]);

        // Vérifier s'il existe déjà une réservation pour cette table dans cette période
        $conflits = HoraireReservation::where('idTable', $request->idTable)
            ->where('statut', 'ACTIVE')
            ->where(function($query) use ($request) {
                $query->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                      ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                      ->orWhere(function($q) use ($request) {
                          $q->where('date_debut', '<=', $request->date_debut)
                            ->where('date_fin', '>=', $request->date_fin);
                      });
            })
            ->exists();

        return response()->json([
            'available' => !$conflits,
            'message' => $conflits ? 'Table réservée sur cette période' : 'Table disponible'
        ]);
    }

    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'idTable' => ['required', 'exists:gestion_salle,idTable'],
            'nombre_personne' => ['required', 'integer', 'min:1', 'max:20'],
            'date_debut' => ['required', 'date', 'after:now'],
            'duree' => ['required', 'integer', 'min:30', 'max:240'], // En minutes
        ]);

        try {
            $compte = auth()->user();
            $client = Client::find($compte->idProprietaire);

            // Calculer la date de fin (date_debut + durée)
            $dateDebut = new \DateTime($validated['date_debut']);
            $dateFin = (clone $dateDebut)->modify('+' . $validated['duree'] . ' minutes');
            
            // Calculer l'échéance (2h30 max, soit 150 minutes)
            $echeance = (clone $dateDebut)->modify('+150 minutes');

            // Vérifier à nouveau la disponibilité
            $conflits = HoraireReservation::where('idTable', $validated['idTable'])
                ->where('statut', 'ACTIVE')
                ->where(function($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                          ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                          ->orWhere(function($q) use ($dateDebut, $dateFin) {
                              $q->where('date_debut', '<=', $dateDebut)
                                ->where('date_fin', '>=', $dateFin);
                          });
                })
                ->exists();

            if ($conflits) {
                return back()
                    ->withErrors(['error' => 'Cette table n\'est plus disponible pour cette période.'])
                    ->withInput();
            }

            // Créer la réservation
            $reservation = HoraireReservation::create([
                'idTable' => $validated['idTable'],
                'idClient' => $client->idClient,
                'statut' => 'ACTIVE',
                'echeance' => $echeance,
                'nombre_personne' => $validated['nombre_personne'],
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'creer_le' => now(),
            ]);

            return redirect()->route('client.reservations.index')
                ->with('success', 'Votre réservation a été créée avec succès !');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de votre réservation.'])
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show(HoraireReservation $reservation)
    {
        // Vérifier que la réservation appartient au client connecté
        $compte = auth()->user();
        if ($reservation->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette réservation');
        }

        $reservation->load('table');

        return view('client.reservations.show', compact('reservation'));
    }

    /**
     * Annuler une réservation (soft delete via changement de statut)
     */
    public function cancel(HoraireReservation $reservation)
    {
        // Vérifier que la réservation appartient au client connecté
        $compte = auth()->user();
        if ($reservation->idClient !== $compte->idProprietaire) {
            abort(403, 'Accès non autorisé à cette réservation');
        }

        // On ne peut annuler que les réservations actives
        if ($reservation->statut !== 'ACTIVE') {
            return back()->withErrors(['error' => 'Cette réservation ne peut plus être annulée.']);
        }

        $reservation->update(['statut' => 'ANNULEE']);

        return back()->with('success', 'Réservation annulée avec succès.');
    }
}
