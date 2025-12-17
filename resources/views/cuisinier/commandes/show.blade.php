@extends('layouts.app')

@section('title', 'Détails Commande #' . $commande->idCommande)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $commande->idCommande }}</h1>
            <p class="text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }} • {{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('cuisinier.dashboard') }}" class="btn btn-outline">
            ← Retour au tableau de bord
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails commande -->
    <div class="lg:col-span-2">
        <div class="card mb-6">
            <h2 class="text-xl font-semibold mb-4">Articles à préparer</h2>
            
            <!-- Plats -->
            @if($commande->composer->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-3 text-primary-700">🍽️ Plats</h3>
                    <div class="space-y-3">
                        @foreach($commande->composer as $composer)
                            <div class="p-4 bg-primary-50 border border-primary-200 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-lg">{{ $composer->plat->nom }}</p>
                                        <p class="text-sm text-gray-600">Prix unitaire: {{ number_format($composer->plat->prix, 2) }} €</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-primary-600">x{{ $composer->quantite }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Boissons -->
            @if($commande->contenir->count() > 0)
                <div>
                    <h3 class="text-lg font-medium mb-3 text-blue-700">🥤 Boissons</h3>
                    <div class="space-y-3">
                        @foreach($commande->contenir as $contenir)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-lg">{{ $contenir->boisson->nom }}</p>
                                        <p class="text-sm text-gray-600">Prix unitaire: {{ number_format($contenir->boisson->prix, 2) }} €</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-blue-600">x{{ $contenir->quantite }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Informations et actions -->
    <div>
        <div class="card mb-6">
            <h3 class="font-semibold mb-4">Informations</h3>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="text-gray-600">Statut</dt>
                    <dd>
                        @if($commande->statut === 'En attente')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($commande->statut === 'En cours')
                            <span class="badge badge-info">En préparation</span>
                        @elseif($commande->statut === 'Prete')
                            <span class="badge badge-success">Prête</span>
                        @else
                            <span class="badge badge-secondary">{{ $commande->statut }}</span>
                        @endif
                    </dd>
                </div>
                
                <div>
                    <dt class="text-gray-600">Table</dt>
                    <dd class="font-medium">{{ $commande->table->numero ?? 'N/A' }}</dd>
                </div>

                <div>
                    <dt class="text-gray-600">Client</dt>
                    <dd class="font-medium">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</dd>
                </div>

                <div>
                    <dt class="text-gray-600">Date/Heure</dt>
                    <dd>{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-gray-600">Montant</dt>
                    <dd class="font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="card">
            <h3 class="font-semibold mb-4">Actions</h3>
            <div class="space-y-3">
                @if($commande->statut === 'En attente')
                    <form method="POST" action="{{ route('cuisinier.commandes.start', $commande) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full">
                            ▶️ Démarrer la préparation
                        </button>
                    </form>
                @elseif($commande->statut === 'En cours')
                    <form method="POST" action="{{ route('cuisinier.commandes.ready', $commande) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-full">
                            ✅ Marquer comme prête
                        </button>
                    </form>
                @elseif($commande->statut === 'Prete')
                    <div class="p-4 bg-green-50 border border-green-200 rounded text-center">
                        <p class="text-green-700 font-medium">✅ Commande prête</p>
                        <p class="text-sm text-green-600">En attente du serveur</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
