@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Tableau de bord Administrateur</h1>
    <p class="text-gray-600">Vue d'ensemble de l'activité du restaurant</p>
</div>

<!-- Cartes de statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Clients -->
    <div class="card bg-gradient-to-br from-primary-500 to-primary-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-100 text-sm">Total Clients</p>
                <p class="text-3xl font-bold">{{ $stats['total_clients'] }}</p>
            </div>
            <svg class="h-12 w-12 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
    </div>

    <!-- Chiffre d'affaires -->
    <div class="card bg-gradient-to-br from-secondary-500 to-secondary-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-secondary-100 text-sm">CA Aujourd'hui</p>
                <p class="text-3xl font-bold">{{ number_format($stats['chiffre_affaires_jour'], 0) }} €</p>
                <p class="text-xs text-secondary-100 mt-1">Total: {{ number_format($stats['chiffre_affaires_total'], 0) }} €</p>
            </div>
            <svg class="h-12 w-12 text-secondary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Commandes -->
    <div class="card bg-gradient-to-br from-accent-500 to-accent-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-accent-100 text-sm">Commandes Aujourd'hui</p>
                <p class="text-3xl font-bold">{{ $stats['commandes_jour'] }}</p>
                <p class="text-xs text-accent-100 mt-1">Total: {{ $stats['total_commandes'] }}</p>
            </div>
            <svg class="h-12 w-12 text-accent-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
    </div>

    <!-- Alertes Stock -->
    <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm">Alertes Stock</p>
                <p class="text-3xl font-bold">{{ $stats['plats_stock_faible'] + $stats['boissons_stock_faible'] }}</p>
                <p class="text-xs text-red-100 mt-1">Articles < 10 unités</p>
            </div>
            <svg class="h-12 w-12 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
    </div>
</div>

<!-- Graphiques et activité -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Graphique 7 derniers jours -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Activité des 7 derniers jours</h2>
        <div class="space-y-3">
            @foreach($statsParJour as $stat)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">{{ $stat['date'] }}</span>
                        <span class="text-gray-600">{{ $stat['commandes'] }} commandes • {{ number_format($stat['ca'], 2) }} €</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $stat['ca'] > 0 ? min(($stat['ca'] / max(array_column($statsParJour, 'ca'))) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Alertes Stock -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">⚠️ Alertes Stock</h2>
        
        @if($alertesStock['plats']->isEmpty() && $alertesStock['boissons']->isEmpty())
            <p class="text-gray-500 text-center py-8">Aucune alerte stock</p>
        @else
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($alertesStock['plats'] as $plat)
                    <div class="flex justify-between items-center p-3 bg-orange-50 border border-orange-200 rounded">
                        <div>
                            <p class="font-medium text-orange-900">{{ $plat->nom }}</p>
                            <p class="text-xs text-orange-700">Plat</p>
                        </div>
                        <span class="badge badge-warning">{{ $plat->quantite }} restants</span>
                    </div>
                @endforeach
                
                @foreach($alertesStock['boissons'] as $boisson)
                    <div class="flex justify-between items-center p-3 bg-orange-50 border border-orange-200 rounded">
                        <div>
                            <p class="font-medium text-orange-900">{{ $boisson->nom }}</p>
                            <p class="text-xs text-orange-700">Boisson</p>
                        </div>
                        <span class="badge badge-warning">{{ $boisson->quantite }} restants</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Activité récente -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Dernières commandes -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Dernières Commandes</h2>
        <div class="space-y-3">
            @forelse($dernieresCommandes as $commande)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">#{{ $commande->idCommande }} - {{ $commande->client->nom ?? 'Client Inconnu' }}</p>
                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</p>
                        <span class="badge badge-sm badge-info">{{ $commande->statut }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Aucune commande récente</p>
            @endforelse
        </div>
    </div>

    <!-- Dernières réservations -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Dernières Réservations</h2>
        <div class="space-y-3">
            @forelse($dernieresReservations as $reservation)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">{{ $reservation->client?->nom ?? 'Inconnu' }} {{ $reservation->client?->prenom }}</p>
                        <p class="text-xs text-gray-600">Table {{ $reservation->table?->numero ?? 'N/A' }} • {{ $reservation->nombre_personne }} pers.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m H:i') }}</p>
                        <span class="badge badge-sm badge-success">{{ $reservation->statut }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Aucune réservation récente</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
    <a href="{{ route('admin.users.index') }}" class="card hover:shadow-lg transition text-center">
        <svg class="h-12 w-12 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <p class="font-semibold">Gérer Utilisateurs</p>
    </a>

    <a href="{{ route('admin.menu.index') }}" class="card hover:shadow-lg transition text-center">
        <svg class="h-12 w-12 text-secondary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <p class="font-semibold">Gérer Menu</p>
    </a>

    <a href="{{ route('admin.stock.index') }}" class="card hover:shadow-lg transition text-center">
        <svg class="h-12 w-12 text-accent-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <p class="font-semibold">Gérer Stock</p>
    </a>

    <a href="{{ route('admin.reports.index') }}" class="card hover:shadow-lg transition text-center">
        <svg class="h-12 w-12 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <p class="font-semibold">Voir Rapports</p>
    </a>
</div>
@endsection
