@extends('layouts.app')

@section('title', 'Gestion des Tables')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Tables</h1>
            <p class="text-gray-600">Gérez le statut des tables</p>
        </div>
        <a href="{{ route('serveur.dashboard') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

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
    <h2 class="text-xl font-semibold mb-6">Plan Interactif</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($tables as $table)
            <div class="relative">
                <div class="p-6 rounded-lg text-center border-2 transition-all
                           @if($table->statut === 'Libre') bg-green-50 border-green-400
                           @elseif($table->statut === 'Occupee') bg-orange-50 border-orange-400
                           @else bg-blue-50 border-blue-400
                           @endif">
                    <div class="text-4xl mb-2">
                        @if($table->statut === 'Libre') 🟢
                        @elseif($table->statut === 'Occupee') 🟠
                        @else 🔵
                        @endif
                    </div>
                    <p class="text-2xl font-bold mb-1">{{ $table->numero ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $table->capacite }} personnes</p>
                    <p class="text-sm font-medium mt-2 
                              @if($table->statut === 'Libre') text-green-700
                              @elseif($table->statut === 'Occupee') text-orange-700
                              @else text-blue-700
                              @endif">
                        {{ $table->statut }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="mt-2 space-y-1">
                    @if($table->statut !== 'Occupee')
                        <form method="POST" action="{{ route('serveur.tables.occupied', $table) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-full">
                                Occuper
                            </button>
                        </form>
                    @endif

                    @if($table->statut === 'Occupee')
                        <form method="POST" action="{{ route('serveur.tables.free', $table) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary w-full">
                                Libérer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

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
