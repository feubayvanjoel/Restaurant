@extends('layouts.app')

@section('title', 'Mon Espace')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Bienvenue, {{ $client->prenom }} !</h1>
    <p class="text-gray-600">Gérez vos commandes et réservations</p>
</div>

<!-- Onglets -->
<div x-data="{ tab: 'commandes' }" class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="tab = 'commandes'" 
                :class="tab === 'commandes' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Mes Commandes ({{ $commandesEnCours->count() }})
            </button>
            <button 
                @click="tab = 'reservations'" 
                :class="tab === 'reservations' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Mes Réservations ({{ $reservationsEnCours->count() }})
            </button>
            <button 
                @click="tab = 'historique'" 
                :class="tab === 'historique' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Historique
            </button>
        </nav>
    </div>

    <!-- Contenu des onglets -->
    <div class="mt-6">
        <!-- Onglet Commandes en cours -->
        <div x-show="tab === 'commandes'">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Commandes en cours</h2>
                <div class="space-x-2">
                    <a href="{{ route('client.menu.index') }}" class="btn btn-outline">Voir le menu</a>
                    <a href="{{ route('client.commandes.create') }}" class="btn btn-primary">Nouvelle commande</a>
                </div>
            </div>

            @if($commandesEnCours->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Vous n'avez pas de commandes en cours.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($commandesEnCours as $commande)
                        <div class="card">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold">Commande #{{ $commande->idCommande }}</h3>
                                <span class="badge 
                                    @if($commande->statut == 'En attente') badge-warning
                                    @elseif($commande->statut == 'En cours') badge-info
                                    @else badge-success
                                    @endif
                                ">
                                    {{ $commande->statut }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Table:</strong> {{ $commande->table->numero ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-lg font-bold text-primary-600 mb-3">
                                {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                            </p>
                            <a href="{{ route('client.commandes.show', ['commande' => $commande->getKey()]) }}" class="btn btn-primary btn-sm w-full">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Onglet Réservations en cours -->
        <div x-show="tab === 'reservations'">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Réservations actives</h2>
                <a href="{{ route('client.reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
            </div>

            @if($reservationsEnCours->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Vous n'avez pas de réservations.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($reservationsEnCours as $reservation)
                        <div class="card">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold">Réservation #{{ $reservation->idHoraireReservation }}</h3>
                                <span class="badge badge-success">{{ $reservation->statut }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Table:</strong> {{ $reservation->table?->numero ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Personnes:</strong> {{ $reservation->nombre_personne }}
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('client.reservations.show', ['reservation' => $reservation->getKey()]) }}" class="btn btn-primary btn-sm w-full">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Onglet Historique -->
        <div x-show="tab === 'historique'">
            <h2 class="text-xl font-semibold mb-4">Historique</h2>
            
            <!-- Historique des commandes -->
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-3">Commandes terminées</h3>
                @if($historiqueCommandes->isEmpty())
                    <p class="text-gray-500">Aucune commande terminée.</p>
                @else
                    <div class="space-y-3">
                        @foreach($historiqueCommandes as $commande)
                            <div class="card">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Commande #{{ $commande->idCommande }}</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</p>
                                        <a href="{{ route('client.commandes.show', ['commande' => $commande->getKey()]) }}" class="text-sm text-primary-600 hover:underline">Détails</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Historique des réservations -->
            <div>
                <h3 class="text-lg font-medium mb-3">Réservations passées</h3>
                @if($historiqueReservations->isEmpty())
                    <p class="text-gray-500">Aucune réservation passée.</p>
                @else
                    <div class="space-y-3">
                        @foreach($historiqueReservations as $reservation)
                            <div class="card">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Réservation #{{ $reservation->idHoraireReservation }}</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-info">{{ $reservation->statut }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
