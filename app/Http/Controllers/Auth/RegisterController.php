<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur d'inscription
 * Permet uniquement aux CLIENTS de s'inscrire
 * Les autres utilisateurs (PERSONNEL) sont créés par l'ADMIN
 */
class RegisterController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'numero' => ['required', 'string', 'max:50'],
            'adresse' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:100', 'unique:compte,login'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'login.unique' => 'Ce login est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        try {
            DB::beginTransaction();

            // Créer le client
            $client = Client::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'numero' => $validated['numero'],
                'adresse' => $validated['adresse'],
            ]);

            // Créer le compte associé
            Compte::create([
                'login' => $validated['login'],
                'password' => Hash::make($validated['password']),
                'idProprietaire' => $client->idClient,
                'role' => 'CLIENT',
                'creer_le' => now(),
            ]);

            DB::commit();

            // Rediriger vers la page de connexion avec message de succès
            return redirect()->route('login')
                ->with('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de votre compte.'])
                ->withInput();
        }
    }
}
