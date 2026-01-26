<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GestionSalle;
use Illuminate\Http\Request;

class TableManagementController extends Controller
{
    public function index()
    {
        $tables = GestionSalle::orderBy('numero')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function markAsOccupied(Request $request, $id)
    {
        $table = GestionSalle::findOrFail($id);
        $table->statut = 'Occupee';
        $table->save();

        return back()->with('success', 'Table marquée comme occupée');
    }

    public function markAsFree(Request $request, $id)
    {
        $table = GestionSalle::findOrFail($id);
        $table->statut = 'Libre';
        $table->save();

        return back()->with('success', 'Table libérée avec succès');
    }
}
