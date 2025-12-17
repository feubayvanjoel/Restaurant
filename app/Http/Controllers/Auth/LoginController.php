<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur de connexion
 * Gère l'authentification des utilisateurs via la table COMPTE
 */
class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la tentative de connexion
     */
    public function login(Request $request)
    {
        // Valider les données du formulaire
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Récupérer le rôle de l'utilisateur
            $role = auth()->user()->role;

            // Rediriger selon le rôle
            return $this->redirectBasedOnRole($role);
        }

        // Si échec, retourner avec erreur
        return back()->withErrors([
            'login' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('login');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Redirection selon le rôle
     */
    protected function redirectBasedOnRole($role)
    {
        $redirections = [
            'ADMIN' => route('admin.dashboard'),
            'CLIENT' => route('client.dashboard'),
            'CAISSIER' => route('caissier.dashboard'),
            'SERVEUR' => route('serveur.dashboard'),
            'CUISINIER' => route('cuisinier.dashboard'),
        ];

        return redirect()->intended($redirections[$role] ?? route('home'));
    }
}
