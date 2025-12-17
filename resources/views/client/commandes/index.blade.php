@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mes Commandes</h1>
            <p class="text-gray-600">Consultez toutes vos commandes</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('client.menu.index') }}" class="btn btn-outline">Voir le menu</a>
            <a href="{{ route('client.commandes.create') }}" class="btn btn-primary">Nouvelle commande</a>
        </div>
    </div>
</div>

@if($commandes->isEmpty())
    <div class="card">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande</h3>
            <p class="text-gray-600 mb-4">Vous n'avez pas encore passé de commande.</p>
            <a href="{{ route('client.menu.index') }}" class="btn btn-primary">
                Voir le menu
            </a>
        </div>
    </div>
@else
    <div class="space-y-4">
        @foreach($commandes as $commande)
            <div class="card hover:shadow-lg transition-shadow">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <!-- Informations de la commande -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-xl font-semibold">Commande #{{ $commande->idCommande }}</h3>
                            <span class="badge 
                                @if($commande->statut == 'En attente') badge-warning
                                @elseif($commande->statut == 'En cours') badge-info
                                @elseif($commande->statut == 'Terminee') badge-success
                                @elseif($commande->statut == 'Annulee') badge-danger
                                @else badge-info
                                @endif
                            ">
                                {{ $commande->statut }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Table:</span> {{ $commande->table->numero ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">Total:</span> 
                                <span class="text-primary-600 font-bold">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                        <a href="{{ route('client.commandes.show', $commande) }}" class="btn btn-primary btn-sm">
                            Voir les détails
                        </a>
                        
                        @if($commande->statut === 'En attente')
                            <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm w-full">
                                    Annuler
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
