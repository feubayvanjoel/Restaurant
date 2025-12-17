@extends('layouts.app')

@section('title', 'Commandes à Servir')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Commandes à Servir</h1>
            <p class="text-gray-600">Servir les commandes prêtes</p>
        </div>
        <a href="{{ route('serveur.dashboard') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<!-- Onglets -->
<div x-data="{ tab: 'prete' }">
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="tab = 'prete'" 
                :class="tab === 'prete' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium"
            >
                Prêtes à servir ({{ $commandesPrete->count() }})
            </button>
            <button 
                @click="tab = 'servies'" 
                :class="tab === 'servies' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium"
            >
                Servies aujourd'hui ({{ $commandesServies->count() }})
            </button>
        </nav>
    </div>

    <!-- Commandes prêtes -->
    <div x-show="tab === 'prete'">
        @if($commandesPrete->isEmpty())
            <div class="card text-center py-12">
                <p class="text-gray-500">Aucune commande prête pour le moment</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($commandesPrete as $commande)
                    <div class="card bg-blue-50 border-2 border-blue-400">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-xl font-bold">Commande #{{ $commande->idCommande }}</h3>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('H:i') }}</p>
                            </div>
                            <span class="badge badge-success">Prête</span>
                        </div>

                        <div class="mb-3">
                            <p class="font-semibold text-lg mb-2">Table {{ $commande->table->numero ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-700">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="font-medium text-sm mb-2">Articles:</p>
                            <div class="space-y-1">
                                @foreach($commande->composer as $composer)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $composer->plat->nom }}</span>
                                        <span class="badge badge-sm badge-primary">x{{ $composer->quantite }}</span>
                                    </div>
                                @endforeach
                                @foreach($commande->contenir as $contenir)
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>🥤 {{ $contenir->boisson->nom }}</span>
                                        <span class="badge badge-sm badge-info">x{{ $contenir->quantite }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('serveur.commandes.served', $commande) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm w-full">
                                    ✅ Servie
                                </button>
                            </form>
                            <a href="{{ route('serveur.commandes.show', $commande) }}" class="btn btn-outline btn-sm">
                                Détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Commandes servies -->
    <div x-show="tab === 'servies'">
        @if($commandesServies->isEmpty())
            <div class="card text-center py-12">
                <p class="text-gray-500">Aucune commande servie aujourd'hui</p>
            </div>
        @else
            <div class="card overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Table</th>
                            <th>Client</th>
                            <th>Heure commande</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandesServies as $commande)
                            <tr>
                                <td class="font-bold">#{{ $commande->idCommande }}</td>
                                <td>Table {{ $commande->table->numero ?? 'N/A' }}</td>
                                <td>{{ $commande->client?->nom ?? 'Inconnu' }}</td>
                                <td>{{ \Carbon\Carbon::parse($commande->horaire)->format('H:i') }}</td>
                                <td class="font-semibold text-primary-600">
                                    {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                                </td>
                                <td>
                                    <a href="{{ route('serveur.commandes.show', $commande) }}" class="btn btn-sm btn-outline">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
