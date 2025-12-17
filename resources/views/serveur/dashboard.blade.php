@extends('layouts.app')

@section('title', 'Service - Dashboard')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🍽️ Service - Dashboard</h1>
            <p class="text-gray-600">Gérez les tables et servez les commandes</p>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="flex space-x-4">
            <div class="text-center">
                <p class="text-sm text-green-600">Tables libres</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['tables_libres'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-orange-600">Tables occupées</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['tables_occupees'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-blue-600">À servir</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['commandes_prete'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <a href="{{ route('serveur.commandes.index') }}" class="card hover:shadow-lg transition bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Commandes à servir</p>
                <p class="text-3xl font-bold">{{ $commandesPrete->count() }}</p>
            </div>
            <svg class="h-12 w-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
    </a>

    <a href="{{ route('serveur.tables.index') }}" class="card hover:shadow-lg transition bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Gestion des tables</p>
                <p class="text-3xl font-bold">{{ $tables->count() }}</p>
            </div>
            <svg class="h-12 w-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
        </div>
    </a>

    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Commandes aujourd'hui</p>
                <p class="text-3xl font-bold">{{ $stats['commandes_jour'] }}</p>
            </div>
            <svg class="h-12 w-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Plan des tables -->
    <div class="card">
<h2 class="text-xl font-semibold mb-4">Plan des Tables</h2>
        
        <div class="grid grid-cols-4 gap-3">
            @foreach($tables as $table)
                <a href="{{ route('serveur.tables.index') }}" 
                   class="p-4 rounded-lg text-center font-semibold transition hover:shadow-lg
                          @if($table->statut === 'Libre') bg-green-100 border-2 border-green-400 text-green-900
                          @elseif($table->statut === 'Occupee') bg-orange-100 border-2 border-orange-400 text-orange-900
                          @else bg-blue-100 border-2 border-blue-400 text-blue-900
                          @endif">
                    <div class="text-2xl mb-1">
                        @if($table->statut === 'Libre') 🟢
                        @elseif($table->statut === 'Occupee') 🟠
                        @else 🔵
                        @endif
                    </div>
                    <p class="text-lg">Table {{ $table->numero ?? 'N/A' }}</p>
                    <p class="text-xs">{{ $table->capacite }} pers.</p>
                    <p class="text-xs font-medium mt-1">{{ $table->statut }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Commandes prêtes -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Commandes Prêtes</h2>
            <a href="{{ route('serveur.commandes.index') }}" class="btn btn-sm btn-primary">Voir toutes</a>
        </div>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($commandesPrete as $commande)
                <div class="p-4 bg-blue-50 border-2 border-blue-400 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-lg">Commande #{{ $commande->idCommande }}</h3>
                            <p class="text-sm text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }}</p>
                        </div>
                        <span class="badge badge-success">Prête</span>
                    </div>

                    <div class="text-sm mb-3">
                        <p class="font-medium">Articles:</p>
                        <ul class="list-disc list-inside text-gray-700">
                            @foreach($commande->composer as $composer)
                                <li>{{ $composer->plat->nom }} (x{{ $composer->quantite }})</li>
                            @endforeach
                            @foreach($commande->contenir as $contenir)
                                <li>{{ $contenir->boisson->nom }} (x{{ $contenir->quantite }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('serveur.commandes.served', $commande) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm w-full">
                            ✅ Marquer comme servie
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucune commande prête</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Actualisation automatique -->
<div class="mt-6 text-center text-sm text-gray-600">
    <p>🔄 Actualisation automatique toutes les 30 secondes</p>
</div>
@endsection

@push('scripts')
<script>
    // Actualisation auto toutes les 30 secondes
    setInterval(() => {
        window.location.reload();
    }, 30000);
</script>
@endpush
