<?php

namespace App\Http\Controllers\Serveur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des commandes pour le SERVEUR
 * Service des commandes prêtes
 */
class CommandeController extends Controller
{
    /**
     * Marquer une commande comme servie
     */
    public function markAsServed(Commande $commande)
    {
        if ($commande->statut !== 'Prete') {
            return back()->withErrors(['error' => 'Cette commande n\'est pas prête.']);
        }

        $commande->update(['statut' => 'Servie']);

        return back()->with('success', 'Commande servie !');
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load(['client', 'table', 'composer.plat', 'contenir.boisson', 'ticket']);

        return view('serveur.commandes.show', compact('commande'));
    }

    /**
     * Liste des commandes à servir
     */
    public function index()
    {
        $commandesPrete = Commande::where('statut', 'Prete')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        $commandesServies = Commande::where('statut', 'Servie')
            ->with(['client', 'table'])
            ->whereDate('horaire', today())
            ->orderBy('horaire', 'desc')
            ->get();

        return view('serveur.commandes.index', compact('commandesPrete', 'commandesServies'));
    }
}
