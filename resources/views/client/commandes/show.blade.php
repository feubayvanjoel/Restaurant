@extends('layouts.app')

@section('title', 'Détails Commande #' . $commande->idCommande)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $commande->idCommande }}</h1>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('client.commandes.index') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails de la commande -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        <span class="badge 
                            @if($commande->statut == 'En attente') badge-warning
                            @elseif($commande->statut == 'En cours') badge-info
                            @elseif($commande->statut == 'Terminee') badge-success
                            @elseif($commande->statut == 'Annulee') badge-danger
                            @endif
                        ">
                            {{ $commande->statut }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Table</dt>
                    <dd class="mt-1 text-gray-900">Table {{ $commande->table->numero ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de commande</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Numéro de ticket</dt>
                    <dd class="mt-1 text-gray-900">#{{ $commande->ticket->idTicket ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Articles commandés -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Articles</h2>
            
            <div class="divide-y">
                <!-- Plats -->
                @foreach($commande->composer as $composer)
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $composer->plat->nom }}</h3>
                            <p class="text-sm text-gray-600">Plat • Quantité: {{ $composer->quantite }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            {{ number_format($composer->plat->prix * $composer->quantite, 2) }} €
                        </p>
                    </div>
                @endforeach

                <!-- Boissons -->
                @foreach($commande->contenir as $contenir)
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $contenir->boisson->nom }}</h3>
                            <p class="text-sm text-gray-600">Boisson • Quantité: {{ $contenir->quantite }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            {{ number_format($contenir->boisson->prix * $contenir->quantite, 2) }} €
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold">Total</span>
                    <span class="text-2xl font-bold text-primary-600">
                        {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions et informations supplémentaires -->
    <div class="space-y-4">
        <!-- Actions -->
        <div class="card">
            <h3 class="font-semibold mb-3">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('client.commandes.ticket', $commande) }}" 
                   class="btn btn-primary w-full"
                   target="_blank">
                    📄 Télécharger le ticket
                </a>

                @if($commande->statut === 'En attente')
                    <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger w-full">
                            ❌ Annuler la commande
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- État de la commande -->
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">État de la commande</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ $commande->statut == 'En attente' || $commande->statut == 'En cours' || $commande->statut == 'Terminee' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                            @if($commande->statut == 'En attente' || $commande->statut == 'En cours' || $commande->statut == 'Terminee')
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Commande reçue</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ $commande->statut == 'En cours' || $commande->statut == 'Terminee' ? 'bg-green-500' : ($commande->statut == 'En attente' ? 'bg-yellow-500' : 'bg-gray-300') }} flex items-center justify-center">
                            @if($commande->statut == 'En cours' || $commande->statut == 'Terminee')
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">En préparation</p>
                        @if($commande->statut == 'En attente')
                            <p class="text-sm text-gray-600">En attente</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ $commande->statut == 'Terminee' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                            @if($commande->statut == 'Terminee')
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Servie</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
