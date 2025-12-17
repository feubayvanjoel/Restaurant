<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Boisson;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion du menu pour l'ADMIN
 * Gère les PLATS et BOISSONS (CRUD complet)
 */
class MenuManagementController extends Controller
{
    /**
     * Afficher la liste des plats et boissons
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'plats');

        $plats = Plat::orderBy('NOM')->get();
        $boissons = Boisson::orderBy('NOM')->get();

        return view('admin.menu.index', compact('plats', 'boissons', 'tab'));
    }

    /**
     * Formulaire de création de plat
     */
    public function createPlat()
    {
        return view('admin.menu.create-plat');
    }

    /**
     * Enregistrer un nouveau plat
     */
    public function storePlat(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prix' => ['required', 'numeric', 'min:0'],
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        Plat::create($validated);

        return redirect()->route('admin.menu.index', ['tab' => 'plats'])
            ->with('success', 'Plat créé avec succès !');
    }

    /**
     * Formulaire d'édition de plat
     */
    public function editPlat(Plat $plat)
    {
        return view('admin.menu.edit-plat', compact('plat'));
    }

    /**
     * Mettre à jour un plat
     */
    public function updatePlat(Request $request, Plat $plat)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prix' => ['required', 'numeric', 'min:0'],
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        $plat->update($validated);

        return redirect()->route('admin.menu.index', ['tab' => 'plats'])
            ->with('success', 'Plat mis à jour avec succès !');
    }

    /**
     * Supprimer un plat
     */
    public function deletePlat(Plat $plat)
    {
        $plat->delete();

        return back()->with('success', 'Plat supprimé avec succès.');
    }

    /**
     * Formulaire de création de boisson
     */
    public function createBoisson()
    {
        return view('admin.menu.create-boisson');
    }

    /**
     * Enregistrer une nouvelle boisson
     */
    public function storeBoisson(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prix' => ['required', 'numeric', 'min:0'],
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        Boisson::create($validated);

        return redirect()->route('admin.menu.index', ['tab' => 'boissons'])
            ->with('success', 'Boisson créée avec succès !');
    }

    /**
     * Formulaire d'édition de boisson
     */
    public function editBoisson(Boisson $boisson)
    {
        return view('admin.menu.edit-boisson', compact('boisson'));
    }

    /**
     * Mettre à jour une boisson
     */
    public function updateBoisson(Request $request, Boisson $boisson)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prix' => ['required', 'numeric', 'min:0'],
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        $boisson->update($validated);

        return redirect()->route('admin.menu.index', ['tab' => 'boissons'])
            ->with('success', 'Boisson mise à jour avec succès !');
    }

    /**
     * Supprimer une boisson
     */
    public function deleteBoisson(Boisson $boisson)
    {
        $boisson->delete();

        return back()->with('success', 'Boisson supprimée avec succès.');
    }
}
