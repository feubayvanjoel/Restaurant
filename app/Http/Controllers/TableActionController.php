<?php

namespace App\Http\Controllers;

use App\Models\GestionSalle;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableActionController extends Controller
{
    // Fetch orders that can be moved to this table
    public function getEligibleOrders($id)
    {
        $table = GestionSalle::findOrFail($id);
        
        // Orders eligible:
        // 1. Status is Active ('En attente', 'Confirmee', 'En preparation', 'Préparée', 'Servie')
        // 2. Number of people fits in the new table
        // 3. (Optional) Not already on THIS table (though moving to same table is harmless but useless)
        
        $orders = Commande::with(['client', 'table'])
            ->whereIn('STATUT', ['En attente', 'Confirmee', 'En préparation', 'Préparée', 'Servie'])
            ->whereIn('STATUT', ['En attente', 'Confirmee', 'En préparation', 'Préparée', 'Servie'])
            ->where(function($query) use ($id) {
                $query->where('IDTABLE', '!=', $id) // Not already here
                      ->orWhereNull('IDTABLE');
            })
            ->orderBy('horaire', 'desc')
            ->get();

        // Filter by logic using the Accessor (places_restantes)
        // Since places_restantes is calculated, we filter the collection
        $orders = $orders->filter(function ($order) use ($table) {
            return $order->NB_PERSONNES <= $table->places_restantes;
        })->values(); // Reset keys

        return response()->json($orders);
    }

    // Assign an order to a table (Move or Seat)
    public function assignOrder(Request $request, $id)
    {
        $table = GestionSalle::findOrFail($id);
        $request->validate([
            'commande_id' => 'required|exists:commande,idCommande'
        ]);

        $order = Commande::findOrFail($request->commande_id);
        $oldTableId = $order->IDTABLE;

        DB::transaction(function () use ($table, $order, $oldTableId) {
            // 1. Updates Order
            $order->IDTABLE = $table->idTable;
            $order->save();

            // 2. Update New Table Status
            $table->statut = 'Occupee';
            $table->save();

            // 3. Check Old Table (if it existed)
            if ($oldTableId) {
                // Check if old table still has active orders
                $remainingOrders = Commande::where('IDTABLE', $oldTableId)
                    ->whereIn('STATUT', ['En attente', 'Confirmee', 'En préparation', 'Préparée', 'Servie'])
                    ->exists();
                
                if (!$remainingOrders) {
                    $oldTable = GestionSalle::find($oldTableId);
                    if ($oldTable) {
                        $oldTable->statut = 'Libre';
                        $oldTable->save();
                    }
                }
            }
        });

        return back()->with('success', 'Table occupée avec succès (Commande #' . $order->idCommande . ')');
    }

    // Free a table (Checkout / Finish active orders)
    public function freeTable($id)
    {
        $table = GestionSalle::findOrFail($id);

        DB::transaction(function () use ($table) {
            // Find active orders on this table
            $activeOrders = Commande::where('IDTABLE', $table->idTable)
                ->whereIn('STATUT', ['En attente', 'Confirmee', 'En préparation', 'Préparée', 'Servie'])
                ->get();

            foreach ($activeOrders as $order) {
                $order->STATUT = 'Payée'; // Or 'Terminée'
                $order->save();
            }

            $table->statut = 'Libre';
            $table->save();
        });

        return back()->with('success', 'Table libérée et commandes clôturées');
    }

    // Create a new table
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|unique:gestion_salle,numero',
            'capacite' => 'required|integer|min:1',
        ]);

        GestionSalle::create([
            'NUMERO' => $request->numero,
            'CAPACITE' => $request->capacite,
            'STATUT' => 'Libre'
        ]);

        return back()->with('success', 'Nouvelle table créée avec succès');
    }
}
