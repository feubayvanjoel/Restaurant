<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Routes d'Authentification
|--------------------------------------------------------------------------
*/

// Page d'accueil (redirige vers login)
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        $redirections = [
            'ADMIN' => 'admin.dashboard',
            'CLIENT' => 'client.dashboard',
            'CAISSIER' => 'caissier.dashboard',
            'SERVEUR' => 'serveur.dashboard',
            'CUISINIER' => 'cuisinier.dashboard',
        ];
        
        if (isset($redirections[$role])) {
            return redirect()->route($redirections[$role]);
        }
    }
    return redirect()->route('login');
});

// Routes publiques (guests only)
Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Inscription (CLIENT uniquement)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Réinitialisation mot de passe
    Route::get('/forgot-password', [PasswordResetController::class, 'showResetRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'verifyCredentials'])->name('password.verify');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Déconnexion (authentifié)
// Déconnexion (authentifié)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Profil (authentifié)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Routes ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{type}/{id}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{type}/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{type}/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Gestion du menu
    Route::get('/menu', [App\Http\Controllers\Admin\MenuManagementController::class, 'index'])->name('menu.index');
    
    // Plats
    Route::get('/menu/plats/create', [App\Http\Controllers\Admin\MenuManagementController::class, 'createPlat'])->name('menu.plats.create');
    Route::post('/menu/plats', [App\Http\Controllers\Admin\MenuManagementController::class, 'storePlat'])->name('menu.plats.store');
    Route::get('/menu/plats/{plat}/edit', [App\Http\Controllers\Admin\MenuManagementController::class, 'editPlat'])->name('menu.plats.edit');
    Route::put('/menu/plats/{plat}', [App\Http\Controllers\Admin\MenuManagementController::class, 'updatePlat'])->name('menu.plats.update');
    Route::delete('/menu/plats/{plat}', [App\Http\Controllers\Admin\MenuManagementController::class, 'deletePlat'])->name('menu.plats.delete');
    
    // Boissons
    Route::get('/menu/boissons/create', [App\Http\Controllers\Admin\MenuManagementController::class, 'createBoisson'])->name('menu.boissons.create');
    Route::post('/menu/boissons', [App\Http\Controllers\Admin\MenuManagementController::class, 'storeBoisson'])->name('menu.boissons.store');
    Route::get('/menu/boissons/{boisson}/edit', [App\Http\Controllers\Admin\MenuManagementController::class, 'editBoisson'])->name('menu.boissons.edit');
    Route::put('/menu/boissons/{boisson}', [App\Http\Controllers\Admin\MenuManagementController::class, 'updateBoisson'])->name('menu.boissons.update');
    Route::delete('/menu/boissons/{boisson}', [App\Http\Controllers\Admin\MenuManagementController::class, 'deleteBoisson'])->name('menu.boissons.delete');
    
    // Gestion du stock
    Route::get('/stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock.index');
    Route::put('/stock/plats/{plat}', [App\Http\Controllers\Admin\StockController::class, 'updatePlatStock'])->name('stock.plats.update');
    Route::put('/stock/boissons/{boisson}', [App\Http\Controllers\Admin\StockController::class, 'updateBoissonStock'])->name('stock.boissons.update');
    
    // Rapports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Routes CLIENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:CLIENT'])->prefix('client')->name('client.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    
    // Menu
    Route::get('/menu', [App\Http\Controllers\Client\MenuController::class, 'index'])->name('menu.index');
    Route::get('/api/plats', [App\Http\Controllers\Client\MenuController::class, 'getPlats'])->name('api.plats');
    Route::get('/api/boissons', [App\Http\Controllers\Client\MenuController::class, 'getBoissons'])->name('api.boissons');
    
    // Commandes
    Route::get('/commandes', [App\Http\Controllers\Client\CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/create', [App\Http\Controllers\Client\CommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes', [App\Http\Controllers\Client\CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [App\Http\Controllers\Client\CommandeController::class, 'show'])->name('commandes.show');
    Route::get('/commandes/{commande}/ticket', [App\Http\Controllers\Client\CommandeController::class, 'downloadTicket'])->name('commandes.ticket');
    Route::post('/commandes/{commande}/cancel', [App\Http\Controllers\Client\CommandeController::class, 'cancel'])->name('commandes.cancel');
    
    // Réservations
    Route::get('/reservations', [App\Http\Controllers\Client\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [App\Http\Controllers\Client\ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [App\Http\Controllers\Client\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [App\Http\Controllers\Client\ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/check-availability', [App\Http\Controllers\Client\ReservationController::class, 'checkAvailability'])->name('reservations.check');
    Route::post('/reservations/{reservation}/cancel', [App\Http\Controllers\Client\ReservationController::class, 'cancel'])->name('reservations.cancel');
});

/*
|--------------------------------------------------------------------------
| Routes CAISSIER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:CAISSIER'])->prefix('caissier')->name('caissier.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Caissier\DashboardController::class, 'index'])->name('dashboard');
    
    // Encaissements
    Route::get('/encaissements', [App\Http\Controllers\Caissier\EncaissementController::class, 'index'])->name('encaissements.index');
    Route::get('/encaissements/{commande}', [App\Http\Controllers\Caissier\EncaissementController::class, 'show'])->name('encaissements.show');
    Route::post('/encaissements/{commande}/process', [App\Http\Controllers\Caissier\EncaissementController::class, 'process'])->name('encaissements.process');
    Route::get('/encaissements-history', [App\Http\Controllers\Caissier\EncaissementController::class, 'history'])->name('encaissements.history');
});

/*
|--------------------------------------------------------------------------
| Routes SERVEUR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:SERVEUR'])->prefix('serveur')->name('serveur.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Serveur\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des commandes
    Route::get('/commandes', [App\Http\Controllers\Serveur\CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{commande}', [App\Http\Controllers\Serveur\CommandeController::class, 'show'])->name('commandes.show');
    Route::post('/commandes/{commande}/served', [App\Http\Controllers\Serveur\CommandeController::class, 'markAsServed'])->name('commandes.served');
    
    // Gestion des tables
    Route::get('/tables', [App\Http\Controllers\Serveur\TableController::class, 'index'])->name('tables.index');
    Route::put('/tables/{table}/status', [App\Http\Controllers\Serveur\TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::post('/tables/{table}/free', [App\Http\Controllers\Serveur\TableController::class, 'markAsFree'])->name('tables.free');
    Route::post('/tables/{table}/occupied', [App\Http\Controllers\Serveur\TableController::class, 'markAsOccupied'])->name('tables.occupied');
});

/*
|--------------------------------------------------------------------------
| Routes CUISINIER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:CUISINIER'])->prefix('cuisinier')->name('cuisinier.')->group(function () {
    // Dashboard (Kanban des commandes)
    Route::get('/dashboard', [App\Http\Controllers\Cuisinier\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des commandes
    Route::get('/commandes/{commande}', [App\Http\Controllers\Cuisinier\CommandeController::class, 'show'])->name('commandes.show');
    Route::post('/commandes/{commande}/start', [App\Http\Controllers\Cuisinier\CommandeController::class, 'startPreparation'])->name('commandes.start');
    Route::post('/commandes/{commande}/ready', [App\Http\Controllers\Cuisinier\CommandeController::class, 'markAsReady'])->name('commandes.ready');
    
    // API pour actualisation
    Route::get('/api/commandes', [App\Http\Controllers\Cuisinier\CommandeController::class, 'getCommandes'])->name('api.commandes');
});


