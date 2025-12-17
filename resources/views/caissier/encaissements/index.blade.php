@extends('layouts.app')

@section('title', 'Encaissements')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Encaissements</h1>
            <p class="text-gray-600">Traiter les paiements</p>
        </div>
        <a href="{{ route('caissier.dashboard') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

@if($commandesAEncaisser->isEmpty())
    <div class="card text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande à encaisser</h3>
        <p class="text-gray-600">Toutes les commandes ont été traitées</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($commandesAEncaisser as $commande)
            <div class="card bg-orange-50 border-2 border-orange-400">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl font-bold">Commande #{{ $commande->idCommande }}</h3>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="badge badge-success">Servie</span>
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-700"><strong>Table:</strong></p>
                        <p class="text-lg font-bold text-primary-600">{{ $commande->table->numero ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-700"><strong>Client:</strong></p>
                        <p class="text-sm">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-700"><strong>Ticket:</strong></p>
                        <p class="text-sm">#{{ $commande->ticket->idTicket ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mb-4 p-4 bg-white rounded border-2 border-primary-400">
                    <p class="text-sm text-gray-600 mb-1">Montant à encaisser</p>
                    <p class="text-3xl font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</p>
                </div>

                <a href="{{ route('caissier.encaissements.show', $commande) }}" class="btn btn-primary w-full">
                    💳 Procéder au paiement
                </a>
            </div>
        @endforeach
    </div>
@endif
@endsection
