<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Client;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur de réinitialisation de mot de passe
 * Permet de réinitialiser le mot de passe en vérifiant login + email
 */
class PasswordResetController extends Controller
{
    /**
     * Afficher le formulaire de demande de réinitialisation
     */
    public function showResetRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Vérifier le login et l'email
     */
    public function verifyCredentials(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        // Chercher le compte
        $compte = Compte::where('login', $request->login)->first();

        if (!$compte) {
            return back()->withErrors(['login' => 'Aucun compte trouvé avec ce login.']);
        }

        // Vérifier l'email selon le rôle
        $emailMatch = false;

        if ($compte->role === 'CLIENT') {
            $client = Client::find($compte->idProprietaire);
            $emailMatch = $client && $client->email === $request->email;
        } else {
            $personnel = Personnel::find($compte->idProprietaire);
            $emailMatch = $personnel && $personnel->email === $request->email;
        }

        if (!$emailMatch) {
            return back()->withErrors(['email' => 'L\'email ne correspond pas à ce compte.']);
        }

        // Stocker le login en session pour l'étape suivante
        $request->session()->put('reset_login', $request->login);

        return view('auth.reset-password', ['login' => $request->login]);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $login = $request->session()->get('reset_login');

        if (!$login) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Session expirée. Veuillez recommencer.']);
        }

        // Mettre à jour le mot de passe
        $compte = Compte::where('login', $login)->first();

        if (!$compte) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Compte introuvable.']);
        }

        $compte->update([
            'password' => Hash::make($request->password),
        ]);

        // Nettoyer la session
        $request->session()->forget('reset_login');

        return redirect()->route('login')
            ->with('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}
