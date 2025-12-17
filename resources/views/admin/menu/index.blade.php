@extends('layouts.app')

@section('title', 'Gestion du Menu')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion du Menu</h1>
            <p class="text-gray-600">Gérez les plats et boissons</p>
        </div>
    </div>
</div>

<!-- Onglets -->
<div x-data="{ tab: '{{ $tab }}' }">
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="tab = 'plats'" 
                :class="tab === 'plats' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                🍽️ Plats ({{ $plats->count() }})
            </button>
            <button 
                @click="tab = 'boissons'" 
                :class="tab === 'boissons' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                🥤 Boissons ({{ $boissons->count() }})
            </button>
        </nav>
    </div>

    <!-- Section Plats -->
    <div x-show="tab === 'plats'">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Plats</h2>
            <a href="{{ route('admin.menu.plats.create') }}" class="btn btn-primary">Ajouter un plat</a>
        </div>

        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité en stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plats as $plat)
                        <tr>
                            <td>{{ $plat->idPlats }}</td>
                            <td class="font-medium">{{ $plat->nom }}</td>
                            <td>{{ number_format($plat->prix, 2) }} €</td>
                            <td>
                                <span class="font-semibold {{ $plat->quantite === 0 ? 'text-red-600' : ($plat->quantite < 10 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $plat->quantite }}
                                </span>
                            </td>
                            <td>
                                @if($plat->quantite === 0)
                                    <span class="badge badge-danger">Rupture</span>
                                @elseif($plat->quantite < 10)
                                    <span class="badge badge-warning">Stock faible</span>
                                @else
                                    <span class="badge badge-success">Disponible</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.menu.plats.edit', $plat) }}" class="btn btn-sm btn-outline">Éditer</a>
                                    <form method="POST" action="{{ route('admin.menu.plats.delete', $plat) }}" 
                                          onsubmit="return confirm('Supprimer ce plat ?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">Aucun plat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Boissons -->
    <div x-show="tab === 'boissons'">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Boissons</h2>
            <a href="{{ route('admin.menu.boissons.create') }}" class="btn btn-primary">Ajouter une boisson</a>
        </div>

        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité en stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boissons as $boisson)
                        <tr>
                            <td>{{ $boisson->idBoissons }}</td>
                            <td class="font-medium">{{ $boisson->nom }}</td>
                            <td>{{ number_format($boisson->prix, 2) }} €</td>
                            <td>
                                <span class="font-semibold {{ $boisson->quantite === 0 ? 'text-red-600' : ($boisson->quantite < 10 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $boisson->quantite }}
                                </span>
                            </td>
                            <td>
                                @if($boisson->quantite === 0)
                                    <span class="badge badge-danger">Rupture</span>
                                @elseif($boisson->quantite < 10)
                                    <span class="badge badge-warning">Stock faible</span>
                                @else
                                    <span class="badge badge-success">Disponible</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.menu.boissons.edit', $boisson) }}" class="btn btn-sm btn-outline">Éditer</a>
                                    <form method="POST" action="{{ route('admin.menu.boissons.delete', $boisson) }}" 
                                          onsubmit="return confirm('Supprimer cette boisson ?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">Aucune boisson</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
