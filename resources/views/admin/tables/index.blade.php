@extends('layouts.app')

@section('title', 'Gestion des Tables (Admin)')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Tables</h1>
            <p class="text-gray-600">Supervision et modification des statuts</p>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline mr-2">
            ← Retour
        </a>
        <button x-data @click="$dispatch('open-create-table-modal')" class="btn btn-primary">
            + Nouvelle Table
        </button>
    </div>
</div>

@include('partials.table_management_modals')

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-green-50 border-green-200">
        <p class="text-sm text-green-700 font-medium">Tables Libres</p>
        <p class="text-4xl font-bold text-green-900">{{ $tables->where('statut', 'Libre')->count() }}</p>
    </div>
    <div class="card bg-orange-50 border-orange-200">
        <p class="text-sm text-orange-700 font-medium">Tables Occupées</p>
        <p class="text-4xl font-bold text-orange-900">{{ $tables->where('statut', 'Occupee')->count() }}</p>
    </div>
    <div class="card bg-blue-50 border-blue-200">
        <p class="text-sm text-blue-700 font-medium">Tables Réservées</p>
        <p class="text-4xl font-bold text-blue-900">{{ $tables->where('statut', 'Reservee')->count() }}</p>
    </div>
</div>

<!-- Plan des tables interactif -->
<div class="card">
    <h2 class="text-xl font-semibold mb-6">Plan Interactif (Admin)</h2>
    
    <div id="table-management-grid">
        @include('partials.tables_grid', ['tables' => $tables, 'showActions' => true])
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridWrapper = document.getElementById('table-management-grid');
        setInterval(() => {
            fetch('{{ route('admin.tables.refresh') }}?showActions=1')
                .then(response => response.text())
                .then(html => {
                    gridWrapper.innerHTML = html;
                })
                .catch(err => console.error('Erreur refresh tables admin:', err));
        }, 2000);
    });
</script>
@endsection

<!-- Légende -->
<div class="card mt-6 bg-gray-50">
    <h3 class="font-semibold mb-3">Légende</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-green-100 border-2 border-green-400 rounded flex items-center justify-center text-2xl">
                🟢
            </div>
            <div>
                <p class="font-medium text-green-900">Libre</p>
                <p class="text-xs text-gray-600">Table disponible</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-orange-100 border-2 border-orange-400 rounded flex items-center justify-center text-2xl">
                🟠
            </div>
            <div>
                <p class="font-medium text-orange-900">Occupée</p>
                <p class="text-xs text-gray-600">Table en cours d'utilisation</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-100 border-2 border-blue-400 rounded flex items-center justify-center text-2xl">
                🔵
            </div>
            <div>
                <p class="font-medium text-blue-900">Réservée</p>
                <p class="text-xs text-gray-600">Table réservée</p>
            </div>
        </div>
    </div>
</div>
@endsection
