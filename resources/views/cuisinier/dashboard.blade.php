@extends('layouts.app')

@section('title', 'Cuisine - Commandes')

@section('content')
<div id="cuisinier-dashboard-content">
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👨‍🍳 Cuisine - Gestion des Commandes</h1>
            <p class="text-gray-600">Gérez la préparation des plats</p>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="flex space-x-4">
            <div class="text-center">
                <p class="text-sm text-gray-600">Aujourd'hui</p>
                <p class="text-2xl font-bold text-primary-600">{{ $stats['total_jour'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-orange-600">En attente</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['en_attente'] }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-blue-600">En cours</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['en_cours'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Système Kanban -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="commandesKanban()">
    <!-- Colonne EN ATTENTE -->
    <div class="bg-orange-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-orange-900">📋 En Attente</h2>
            <span class="badge badge-warning">{{ $commandesEnAttente->count() }}</span>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto">
            @forelse($commandesEnAttente as $commande)
                <div class="card bg-white hover:shadow-lg transition">
                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-lg">Commande #{{ $commande->idCommande }}</h3>
                            <p class="text-xs text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }} ({{ $commande->table->capacite ?? '?' }} pers.)</p>
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($commande->horaire)->format('H:i') }}
                        </span>
                    </div>

                    <!-- Articles -->
                    <div class="mb-3 space-y-1">
                        @foreach($commande->composer as $composer)
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $composer->plat->nom }}</span>
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

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('cuisinier.commandes.start', $commande) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm w-full">
                                ▶️ Démarrer
                            </button>
                        </form>
                        <a href="{{ route('cuisinier.commandes.show', $commande) }}" class="btn btn-outline btn-sm">
                            👁️
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucune commande en attente</p>
            @endforelse
        </div>
    </div>

    <!-- Colonne EN COURS -->
    <div class="bg-blue-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-blue-900">🔥 En Préparation</h2>
            <span class="badge badge-info">{{ $commandesEnCours->count() }}</span>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto">
            @forelse($commandesEnCours as $commande)
                <div class="card bg-white hover:shadow-lg transition border-2 border-blue-400">
                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-lg">Commande #{{ $commande->idCommande }}</h3>
                            <p class="text-xs text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }} ({{ $commande->table->capacite ?? '?' }} pers.)</p>
                        </div>
                        <span class="text-xs text-blue-600 font-medium">
                            En préparation...
                        </span>
                    </div>

                    <!-- Articles -->
                    <div class="mb-3 space-y-1">
                        @foreach($commande->composer as $composer)
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $composer->plat->nom }}</span>
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

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('cuisinier.commandes.ready', $commande) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm w-full">
                                ✅ Prête
                            </button>
                        </form>
                        <a href="{{ route('cuisinier.commandes.show', $commande) }}" class="btn btn-outline btn-sm">
                            👁️
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucune commande en préparation</p>
            @endforelse
        </div>
    </div>

    <!-- Colonne PRÊTES -->
    <div class="bg-green-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-green-900">✅ Prêtes à Servir</h2>
            <span class="badge badge-success">{{ $commandesPrete->count() }}</span>
        </div>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto">
            @forelse($commandesPrete as $commande)
                <div class="card bg-white hover:shadow-lg transition border-2 border-green-400">
                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-lg">Commande #{{ $commande->idCommande }}</h3>
                            <p class="text-xs text-gray-600">Table {{ $commande->table->numero ?? 'N/A' }} ({{ $commande->table->capacite ?? '?' }} pers.)</p>
                        </div>
                        <span class="badge badge-success">Prête</span>
                    </div>

                    <!-- Articles -->
                    <div class="mb-3 space-y-1">
                        @foreach($commande->composer as $composer)
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $composer->plat->nom }}</span>
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

                    <!-- Info -->
                    <p class="text-xs text-green-700 text-center">En attente du serveur</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucune commande prête</p>
            @endforelse
        </div>
    </div>
</div>

</div> {{-- Close Kanban Wrapper --}}
</div> {{-- Close Main Wrapper --}}
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dashboardContent = document.getElementById('cuisinier-dashboard-content');
        
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('cuisinier-dashboard-content').innerHTML;
                    dashboardContent.innerHTML = newContent;
                })
                .catch(err => console.error('Erreur refresh cuisinier:', err));
        }, 2000);
    });

    function commandesKanban() {
        return {
            init() {
                // Alpine init if needed
            }
        }
    }
</script>
@endpush
