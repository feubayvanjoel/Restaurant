@extends('layouts.app')

@section('title', 'Détails Commande #' . $commande->idCommande)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $commande->idCommande }}</h1>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('serveur.commandes.index') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-600">Table</dt>
                    <dd class="text-2xl font-bold text-primary-600">{{ $commande->table->numero ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Client</dt>
                    <dd class="font-medium">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Statut</dt>
                    <dd>
                        <span class="badge 
                            @if($commande->statut === 'Prete') badge-success
                            @elseif($commande->statut === 'Servie') badge-info
                            @else badge-warning
                            @endif
                        ">
                            {{ $commande->statut }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Montant</dt>
                    <dd class="text-xl font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Articles</h2>
            
            <div class="space-y-3">
                @foreach($commande->composer as $composer)
                    <div class="p-4 bg-primary-50 border border-primary-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-lg">{{ $composer->plat->nom }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($composer->plat->prix, 2) }} € / unité</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-primary-600">x{{ $composer->quantite }}</p>
                                <p class="text-sm font-medium">{{ number_format($composer->plat->prix * $composer->quantite, 2) }} €</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($commande->contenir as $contenir)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-lg">🥤 {{ $contenir->boisson->nom }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($contenir->boisson->prix, 2) }} € / unité</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">x{{ $contenir->quantite }}</p>
                                <p class="text-sm font-medium">{{ number_format($contenir->boisson->prix * $contenir->quantite, 2) }} €</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div>
        <div class="card sticky top-6">
            <h3 class="font-semibold mb-4">Actions</h3>
            
            @if($commande->statut === 'Prete')
                <form method="POST" action="{{ route('serveur.commandes.served', $commande) }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary w-full mb-3">
                        ✅ Marquer comme servie
                    </button>
                </form>
            @elseif($commande->statut === 'Servie')
                <div class="p-4 bg-green-50 border border-green-200 rounded mb-3">
                    <p class="text-green-700 font-medium text-center">✅ Commande servie</p>
                </div>
            @endif

            <hr class="my-4">

            <div class="space-y-2 text-sm">
                <p class="font-medium">Informations ticket</p>
                <p class="text-gray-600">Ticket: #{{ $commande->ticket->idTicket ?? 'N/A' }}</p>
                <p class="text-gray-600">Date: {{ \Carbon\Carbon::parse($commande->ticket->dateTicket ?? $commande->horaire)->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
