<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware pour vérifier le rôle de l'utilisateur connecté
 * Utilisation: Route::middleware(['auth', 'role:ADMIN,CLIENT'])
 */
class CheckRole
{
    /**
     * Gérer la requête entrante
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles - Rôles autorisés (ADMIN, CLIENT, CAISSIER, SERVEUR, CUISINIER)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Récupérer le rôle de l'utilisateur depuis la table COMPTE
        $userRole = auth()->user()->role;

        // Vérifier si le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.');
        }

        return $next($request);
    }
}
