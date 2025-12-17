@extends('layouts.app')

@section('title', 'Caisse - Dashboard')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">💰 Caisse - Dashboard</h1>
            <p class="text-gray-600">Gérez les encaissements</p>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="flex space-x-4">
            <div class="text-center">
                <p class="text-sm text-orange-600">À encaisser</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['a_encaisser'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-green-600">CA Aujourd'hui</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['ca_jour'], 0) }} €</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-blue-600">Tickets</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['tickets_jour'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Cartes statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Chiffre d'Affaires Jour</p>
        <p class="text-3xl font-bold">{{ number_format($stats['ca_jour'], 2) }} €</p>
    </div>

    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Ticket Moyen</p>
        <p class="text-3xl font-bold">{{ number_format($stats['ticket_moyen'], 2) }} €</p>
    </div>

    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Nombre de Tickets</p>
        <p class="text-3xl font-bold">{{ $stats['tickets_jour'] }}</p>
    </div>

    <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <p class="text-orange-100 text-sm">À Encaisser</p>
        <p class="text-3xl font-bold">{{ $stats['a_encaisser'] }}</p>
    </div>
</div>

<!-- Actions rapides -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <a href="{{ route('caissier.encaissements.index') }}" class="card hover:shadow-lg transition bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Encaissements en attente</p>
                <p class="text-3xl font-bold">{{ $commandesAEncaisser->count() }}</p>
            </div>
            <svg class="h-12 w-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
    </a>

    <a href="{{ route('caissier.encaissements.history') }}" class="card hover:shadow-lg transition bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Historique du jour</p>
                <p class="text-3xl font-bold">{{ $encaissementsDuJour->count() }}</p>
            </div>
            <svg class="h-12 w-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Commandes à encaisser -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Commandes à Encaisser</h2>
            <a href="{{ route('caissier.encaissements.index') }}" class="btn btn-sm btn-primary">Voir toutes</a>
        </div>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($commandesAEncaisser as $commande)
                <div class="p-4 bg-orange-50 border-2 border-orange-400 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-lg">Commande #{{ $commande->idCommande }}</h3>
                            <p class="text-sm text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</p>
                        </div>
                        <span class="text-xl font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</span>
                    </div>

                    <a href="{{ route('caissier.encaissements.show', $commande) }}" class="btn btn-primary btn-sm w-full mt-2">
                        💳 Encaisser
                    </a>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucune commande à encaisser</p>
            @endforelse
        </div>
    </div>

    <!-- Derniers encaissements -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Derniers Encaissements</h2>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($encaissementsDuJour->take(10) as $ticket)
                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">Ticket #{{ $ticket->idTicket }}</p>
                            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($ticket->dateTicket)->format('H:i') }}</p>
                            @if($ticket->commande)
                                <p class="text-xs text-gray-600">Table {{ $ticket->commande->table->numero ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <p class="font-bold text-green-600">{{ number_format($ticket->prix, 2) }} €</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucun encaissement aujourd'hui</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
