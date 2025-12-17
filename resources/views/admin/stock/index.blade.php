@extends('layouts.app')

@section('title', 'Gestion du Stock')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Gestion du Stock</h1>
    <p class="text-gray-600">Suivez et mettez à jour les quantités en stock</p>
</div>

<!-- Alertes -->
@if($platsRupture->count() > 0 || $boissonsRupture->count() > 0)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <p class="font-semibold">⚠️ {{ $platsRupture->count() + $boissonsRupture->count() }} article(s) en rupture de stock !</p>
    </div>
@endif

@if($platsStockFaible->count() > 0 || $boissonsStockFaible->count() > 0)
    <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded mb-4">
        <p class="font-semibold">⚠️ {{ $platsStockFaible->count() + $boissonsStockFaible->count() }} article(s) en stock faible (< 10 unités)</p>
    </div>
@endif

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card bg-red-50 border-red-200">
        <p class="text-sm text-red-700 font-medium">Rupture de stock</p>
        <p class="text-3xl font-bold text-red-900">{{ $platsRupture->count() + $boissonsRupture->count() }}</p>
    </div>
    
    <div class="card bg-orange-50 border-orange-200">
        <p class="text-sm text-orange-700 font-medium">Stock faible</p>
        <p class="text-3xl font-bold text-orange-900">{{ $platsStockFaible->count() + $boissonsStockFaible->count() }}</p>
    </div>
    
    <div class="card bg-green-50 border-green-200">
        <p class="text-sm text-green-700 font-medium">Total Plats</p>
        <p class="text-3xl font-bold text-green-900">{{ $plats->count() }}</p>
    </div>
    
    <div class="card bg-blue-50 border-blue-200">
        <p class="text-sm text-blue-700 font-medium">Total Boissons</p>
        <p class="text-3xl font-bold text-blue-900">{{ $boissons->count() }}</p>
    </div>
</div>

<!-- Onglets -->
<div x-data="{ tab: 'plats' }">
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="tab = 'plats'" 
                :class="tab === 'plats' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium"
            >
                Stock Plats
            </button>
            <button 
                @click="tab = 'boissons'" 
                :class="tab === 'boissons' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium"
            >
                Stock Boissons
            </button>
        </nav>
    </div>

    <!-- Table Plats -->
    <div x-show="tab === 'plats'">
        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                        <th>Mise à jour rapide</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plats as $plat)
                        <tr class="{{ $plat->quantite === 0 ? 'bg-red-50' : ($plat->quantite < 10 ? 'bg-orange-50' : '') }}">
                            <td>{{ $plat->idPlats }}</td>
                            <td class="font-medium">{{ $plat->nom }}</td>
                            <td>{{ number_format($plat->prix, 2) }} €</td>
                            <td>
                                <span class="text-2xl font-bold {{ $plat->quantite === 0 ? 'text-red-600' : ($plat->quantite < 10 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $plat->quantite }}
                                </span>
                            </td>
                            <td>
                                @if($plat->quantite === 0)
                                    <span class="badge badge-danger">Rupture</span>
                                @elseif($plat->quantite < 10)
                                    <span class="badge badge-warning">Faible</span>
                                @else
                                    <span class="badge badge-success">OK</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.stock.plats.update', $plat) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantite" value="{{ $plat->quantite }}" 
                                           min="0" class="input w-24" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table Boissons -->
    <div x-show="tab === 'boissons'">
        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                        <th>Mise à jour rapide</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boissons as $boisson)
                        <tr class="{{ $boisson->quantite === 0 ? 'bg-red-50' : ($boisson->quantite < 10 ? 'bg-orange-50' : '') }}">
                            <td>{{ $boisson->idBoissons }}</td>
                            <td class="font-medium">{{ $boisson->nom }}</td>
                            <td>{{ number_format($boisson->prix, 2) }} €</td>
                            <td>
                                <span class="text-2xl font-bold {{ $boisson->quantite === 0 ? 'text-red-600' : ($boisson->quantite < 10 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $boisson->quantite }}
                                </span>
                            </td>
                            <td>
                                @if($boisson->quantite === 0)
                                    <span class="badge badge-danger">Rupture</span>
                                @elseif($boisson->quantite < 10)
                                    <span class="badge badge-warning">Faible</span>
                                @else
                                    <span class="badge badge-success">OK</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.stock.boissons.update', $boisson) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantite" value="{{ $boisson->quantite }}" 
                                           min="0" class="input w-24" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
