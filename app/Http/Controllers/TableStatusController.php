<?php

namespace App\Http\Controllers;

use App\Models\GestionSalle;
use Illuminate\Http\Request;

class TableStatusController extends Controller
{
    /**
     * Renvoie le HTML de la grille des tables pour l'actualisation silencieuse (AJAX)
     */
    public function refresh(Request $request)
    {
        $tables = GestionSalle::orderBy('numero')->get();
        // Check "showActions" query param (boolean-ish)
        $showActions = $request->query('showActions') || $request->input('showActions');

        return view('partials.tables_grid', compact('tables', 'showActions'));
    }
}
