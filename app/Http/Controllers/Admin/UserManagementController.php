<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Personnel;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur de gestion des utilisateurs pour l'ADMIN
 * Gère les CLIENTS et le PERSONNEL (CRUD complet)
 */
class UserManagementController extends Controller
{
    /**
     * Afficher la liste de tous les utilisateurs
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, clients, personnel

        $clients = collect();
        $personnel = collect();

        if ($type === 'all' || $type === 'clients') {
            $clients = Client::with('compte')->get();
        }

        if ($type === 'all' || $type === 'personnel') {
            $personnel = Personnel::with('compte')->get();
        }

        return view('admin.users.index', compact('clients', 'personnel', 'type'));
    }

    /**
     * Afficher le formulaire de création d'utilisateur
     */
    public function create(Request $request)
    {
        $userType = $request->get('type', 'client'); // client ou personnel
        
        return view('admin.users.create', compact('userType'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => ['required', 'in:client,personnel'],
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'numero' => ['required', 'string', 'max:50'],
            'adresse' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:100', 'unique:compte,login'],
            'password' => ['required', 'string', 'min:6'],
        ];

        if ($request->type === 'personnel') {
            $rules['poste'] = ['required', 'in:ADMIN,CAISSIER,CUISINIER,SERVEUR'];
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            if ($validated['type'] === 'client') {
                // Créer le client
                $user = Client::create([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'numero' => $validated['numero'],
                    'adresse' => $validated['adresse'],
                ]);

                $role = 'CLIENT';
            } else {
                // Créer le personnel
                $user = Personnel::create([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'numero' => $validated['numero'],
                    'adresse' => $validated['adresse'],
                    'poste' => $validated['poste'],
                ]);

                $role = $validated['poste'];
            }

            // Créer le compte
            Compte::create([
                'login' => $validated['login'],
                'password' => Hash::make($validated['password']),
                'idProprietaire' => $validated['type'] === 'client' ? $user->idClient : $user->idPersonnel,
                'role' => $role,
                'creer_le' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($type, $id)
    {
        if ($type === 'client') {
            $user = Client::with('compte')->findOrFail($id);
        } else {
            $user = Personnel::with('compte')->findOrFail($id);
        }

        return view('admin.users.edit', compact('user', 'type'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $type, $id)
    {
        $rules = [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'numero' => ['required', 'string', 'max:50'],
            'adresse' => ['required', 'string', 'max:255'],
        ];

        if ($type === 'personnel') {
            $rules['poste'] = ['required', 'in:ADMIN,CAISSIER,CUISINIER,SERVEUR'];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6'];
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            if ($type === 'client') {
                $user = Client::findOrFail($id);
                $user->update([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'numero' => $validated['numero'],
                    'adresse' => $validated['adresse'],
                ]);
            } else {
                $user = Personnel::findOrFail($id);
                $user->update([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'numero' => $validated['numero'],
                    'adresse' => $validated['adresse'],
                    'poste' => $validated['poste'],
                ]);

                // Mettre à jour le rôle du compte si le poste change
                $user->compte->update(['role' => $validated['poste']]);
            }

            // Mettre à jour le mot de passe si fourni
            if ($request->filled('password')) {
                $user->compte->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour'])
                ->withInput();
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($type, $id)
    {
        try {
            DB::beginTransaction();

            if ($type === 'client') {
                $user = Client::findOrFail($id);
            } else {
                $user = Personnel::findOrFail($id);
            }

            // Le trigger DB s'occupera du compte, mais on peut le faire explicitement pour être sûr
            if ($user->compte) {
                $user->compte->delete();
            }
            
            $user->delete();

            DB::commit();

            return back()->with('success', 'Utilisateur supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }
}
