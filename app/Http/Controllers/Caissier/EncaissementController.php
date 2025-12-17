<?php

namespace App\Http\Controllers\Caissier;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des encaissements pour le CAISSIER
 */
class EncaissementController extends Controller
{
    /**
     * Afficher la page d'encaissement
     */
    public function index()
    {
        $commandesAEncaisser = Commande::where('statut', 'Servie')
            ->with(['client', 'table', 'ticket', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        return view('caissier.encaissements.index', compact('commandesAEncaisser'));
    }

    /**
     * Afficher le détail d'une commande pour encaissement
     */
    public function show(Commande $commande)
    {
        if ($commande->statut !== 'Servie') {
            return redirect()->route('caissier.encaissements.index')
                ->withErrors(['error' => 'Cette commande n\'est pas prête pour l\'encaissement.']);
        }

        $commande->load(['client', 'table', 'ticket', 'composer.plat', 'contenir.boisson']);

        return view('caissier.encaissements.show', compact('commande'));
    }

    /**
     * Encaisser une commande (marquer comme payée/terminée)
     */
    public function process(Commande $commande, Request $request)
    {
        $validated = $request->validate([
            'mode_paiement' => ['required', 'in:Especes,Carte,Mobile'],
        ]);

        if ($commande->statut !== 'Servie') {
            return back()->withErrors(['error' => 'Cette commande n\'est pas prête pour l\'encaissement.']);
        }

        // Marquer la commande comme terminée
        $commande->update(['statut' => 'Terminee']);

        // Note: Le mode de paiement pourrait être enregistré dans une table PAIEMENT
        // Pour l'instant on le stocke en session pour affichage
        session()->flash('mode_paiement', $validated['mode_paiement']);

        return redirect()->route('caissier.dashboard')
            ->with('success', 'Encaissement effectué avec succès !');
    }

    /**
     * Historique des encaissements
     */
    public function history()
    {
        $encaissements = Commande::where('statut', 'Terminee')
            ->with(['client', 'table', 'ticket'])
            ->whereDate('horaire', today())
            ->orderBy('horaire', 'desc')
            ->get();

        $stats = [
            'total_jour' => Ticket::whereDate('dateTicket', today())->sum('prix'),
            'nombre_tickets' => $encaissements->count(),
            'ticket_moyen' => $encaissements->avg(function($commande) {
                return $commande->ticket->prix ?? 0;
            }) ?? 0,
        ];

        return view('caissier.encaissements.history', compact('encaissements', 'stats'));
    }
}
