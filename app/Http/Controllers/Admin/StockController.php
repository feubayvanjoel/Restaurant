<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Boisson;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion du stock pour l'ADMIN
 * Suivi des quantités et alertes
 */
class StockController extends Controller
{
    /**
     * Afficher le tableau de bord du stock
     */
    public function index()
    {
        // Plats avec alertes de stock
        $plats = Plat::orderBy('quantite', 'asc')->get();
        $platsStockFaible = Plat::where('quantite', '<', 10)->orderBy('quantite')->get();
        $platsRupture = Plat::where('quantite', '=', 0)->get();

        // Boissons avec alertes
        $boissons = Boisson::orderBy('quantite', 'asc')->get();
        $boissonsStockFaible = Boisson::where('quantite', '<', 10)->orderBy('quantite')->get();
        $boissonsRupture = Boisson::where('quantite', '=', 0)->get();

        return view('admin.stock.index', compact(
            'plats',
            'boissons',
            'platsStockFaible',
            'platsRupture',
            'boissonsStockFaible',
            'boissonsRupture'
        ));
    }

    /**
     * Mettre à jour le stock d'un plat
     */
    public function updatePlatStock(Request $request, Plat $plat)
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        $plat->update(['quantite' => $validated['quantite']]);

        return back()->with('success', 'Stock mis à jour avec succès.');
    }

    /**
     * Mettre à jour le stock d'une boisson
     */
    public function updateBoissonStock(Request $request, Boisson $boisson)
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        $boisson->update(['quantite' => $validated['quantite']]);

        return back()->with('success', 'Stock mis à jour avec succès.');
    }
}
