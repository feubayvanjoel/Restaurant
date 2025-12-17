@extends('layouts.app')

@section('title', 'Notre Menu')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Notre Menu</h1>
    <p class="text-gray-600">Découvrez nos plats et boissons</p>
</div>

<!-- Menu du jour (si disponible) -->
@if(!empty($menuDuJour))
<div class="bg-accent-50 border-2 border-accent-400 rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-accent-900 mb-4">🌟 Menu du Jour</h2>
    <!-- Affichage du menu du jour à implémenter -->
</div>
@endif

<!-- Conteneur principal avec panier -->
<div x-data="menuManager()" class="mb-6">
    <!-- Onglets Plats/Boissons -->
    <div class="mb-6">
            <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button
                    type="button"
                    @click="tab = 'plats'"
                    :class="tab === 'plats' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg"
                >
                    🍽️ Plats ({{ count($plats) }})
                </button>
                <button
                    type="button"
                    @click="tab = 'boissons'"
                    :class="tab === 'boissons' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg"
                >
                    🥤 Boissons ({{ count($boissons) }})
                </button>
            </nav>
        </div>

        <!-- Grille des Plats -->
        <div x-show="tab === 'plats'">
            @if($plats->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Aucun plat disponible pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($plats as $plat)
                        <div class="card hover:shadow-xl transition-shadow">
                            <div class="mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $plat->nom }}</h3>
                                @if($plat->quantite < 10)
                                    <span class="badge badge-warning text-xs">Stock limité ({{ $plat->quantite }})</span>
                                @endif
                            </div>

                            <p class="text-2xl font-bold text-primary-600 mb-4">
                                {{ number_format($plat->prix, 2) }} €
                            </p>

                            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                <span>
                                    <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Stock: {{ $plat->quantite }}
                                </span>
                            </div>

                            <button
                                type="button"
                                @click='addToCart({{ json_encode(["id" => $plat->idPlats, "nom" => $plat->nom, "prix" => $plat->prix, "type" => "plat"]) }})'
                                class="btn btn-primary w-full"
                            >
                                Ajouter au panier
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Grille des Boissons -->
        <div x-show="tab === 'boissons'">
            @if($boissons->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Aucune boisson disponible pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($boissons as $boisson)
                        <div class="card hover:shadow-xl transition-shadow">
                            <div class="mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $boisson->nom }}</h3>
                                @if($boisson->quantite < 10)
                                    <span class="badge badge-warning text-xs">Stock limité ({{ $boisson->quantite }})</span>
                                @endif
                            </div>

                            <p class="text-2xl font-bold text-primary-600 mb-4">
                                {{ number_format($boisson->prix, 2) }} €
                            </p>

                            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                <span>
                                    <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Stock: {{ $boisson->quantite }}
                                </span>
                            </div>

                            <button
                                type="button"
                                @click='addToCart({{ json_encode(["id" => $boisson->idBoissons, "nom" => $boisson->nom, "prix" => $boisson->prix, "type" => "boisson"]) }})'
                                class="btn btn-primary w-full"
                            >
                                Ajouter au panier
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Icône flottante du panier -->
    <button
        type="button"
        @click="showCart = !showCart"
        x-show="true"
        x-transition
        class="fixed bottom-6 right-6 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl z-40 transition-all duration-300 hover:scale-110"
        :class="cart.length > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 hover:bg-gray-500'"
    >
        <span class="relative">
            <!-- Icône panier -->
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>

            <!-- Badge avec nombre d'articles -->
            <span
                x-show="cart.length > 0"
                x-transition
                class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-white shadow-lg"
                x-text="cart.length"
            ></span>
        </span>
    </button>

    <!-- Panier flottant -->
    <div
        x-show="showCart && cart.length > 0"
        x-transition
        class="fixed bottom-24 right-6 bg-white shadow-2xl rounded-lg p-5 max-w-sm z-50 border-l-4 border-blue-600"
    >
        <div class="flex justify-between items-center mb-4 pb-3 border-b-2 border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Panier <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="cart.length"></span></h3>
            <button type="button" @click="showCart = false" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="max-h-64 overflow-y-auto mb-4">
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex justify-between items-center py-3 border-b border-gray-100 hover:bg-gray-50 px-2 rounded">
                    <div class="flex-1">
                        <p class="font-medium text-sm text-gray-800" x-text="item.nom"></p>
                        <p class="text-xs text-gray-500 mt-1">Prix: <span x-text="item.prix.toFixed(2)"></span> € × <span x-text="item.quantite"></span></p>
                        <p class="text-xs font-semibold text-blue-600 mt-1" x-text="(item.prix * item.quantite).toFixed(2) + ' €'"></p>
                    </div>
                    <div class="flex items-center space-x-2 ml-2">
                        <button type="button" @click="updateQuantity(index, -1)" class="btn btn-sm bg-red-100 hover:bg-red-200 text-red-600 px-2 py-1 rounded font-bold">−</button>
                        <span class="font-bold text-gray-800 w-6 text-center" x-text="item.quantite"></span>
                        <button type="button" @click="updateQuantity(index, 1)" class="btn btn-sm bg-green-100 hover:bg-green-200 text-green-600 px-2 py-1 rounded font-bold">+</button>
                    </div>
                </div>
            </template>
        </div>

        <div class="border-t-2 border-gray-200 pt-3 mb-4">
            <div class="flex justify-between items-center font-bold text-lg">
                <span class="text-gray-700">Total:</span>
                <span class="text-blue-600 text-xl" x-text="getTotal().toFixed(2) + ' €'"></span>
            </div>
        </div>

        <button type="button" @click="proceedToOrder" class="btn btn-primary w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition">
            Procéder à la commande
        </button>
    </div>
</div>
@endsection
