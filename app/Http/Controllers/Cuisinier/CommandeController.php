<?php

namespace App\Http\Controllers\Cuisinier;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des commandes pour le CUISINIER
 * Permet de changer le statut des commandes
 */
class CommandeController extends Controller
{
    /**
     * Démarrer la préparation d'une commande
     */
    public function startPreparation(Commande $commande)
    {
        if ($commande->statut !== 'En attente') {
            return back()->withErrors(['error' => 'Cette commande n\'est pas en attente.']);
        }

        $commande->update(['statut' => 'En cours']);

        return back()->with('success', 'Préparation démarrée !');
    }

    /**
     * Marquer une commande comme prête
     */
    public function markAsReady(Commande $commande)
    {
        if ($commande->statut !== 'En cours') {
            return back()->withErrors(['error' => 'Cette commande n\'est pas en cours de préparation.']);
        }

        $commande->update(['statut' => 'Prete']);

        return back()->with('success', 'Commande prête à servir !');
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load(['client', 'table', 'composer.plat', 'contenir.boisson', 'ticket']);

        return view('cuisinier.commandes.show', compact('commande'));
    }

    /**
     * API: Récupérer les commandes en JSON (pour actualisation AJAX)
     */
    public function getCommandes()
    {
        $commandesEnAttente = Commande::where('statut', 'En attente')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        $commandesEnCours = Commande::where('statut', 'En cours')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->get();

        $commandesPrete = Commande::where('statut', 'Prete')
            ->with(['client', 'table', 'composer.plat', 'contenir.boisson'])
            ->orderBy('horaire', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'en_attente' => $commandesEnAttente,
            'en_cours' => $commandesEnCours,
            'pretes' => $commandesPrete,
        ]);
    }
}
