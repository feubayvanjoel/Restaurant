<?php

namespace App\Http\Controllers\Serveur;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des tables pour le SERVEUR
 */
class TableController extends Controller
{
    /**
     * Afficher toutes les tables
     */
    public function index()
    {
        $tables = RestaurantTable::orderBy('numero')->get();

        return view('serveur.tables.index', compact('tables'));
    }

    /**
     * Changer le statut d'une table
     */
    public function updateStatus(RestaurantTable $table, Request $request)
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:Libre,Occupee,Reservee'],
        ]);

        $table->update(['statut' => $validated['statut']]);

        return back()->with('success', 'Statut de la table mis à jour !');
    }

    /**
     * Marquer une table comme libre
     */
    public function markAsFree(RestaurantTable $table)
    {
        $table->update(['statut' => 'Libre']);

        return back()->with('success', 'Table libérée !');
    }

    /**
     * Marquer une table comme occupée
     */
    public function markAsOccupied(RestaurantTable $table)
    {
        $table->update(['statut' => 'Occupee']);

        return back()->with('success', 'Table marquée comme occupée !');
    }
}
