@extends('layouts.app')

@section('title', 'Nouvelle Commande')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Nouvelle Commande</h1>
    <p class="text-gray-600">Finalisez votre commande</p>
</div>

<!-- Messages d'erreur -->
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $error)
            <p class="text-sm">• {{ $error }}</p>
        @endforeach
    </div>
@endif

<div x-data="{
    cart: [],
    selectedTable: '',
    
    init() {
        // Récupérer le panier depuis sessionStorage
        const savedCart = sessionStorage.getItem('cart');
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
        }
    },
    
    updateQuantity(index, delta) {
        this.cart[index].quantite += delta;
        if (this.cart[index].quantite <= 0) {
            this.cart.splice(index, 1);
        }
        sessionStorage.setItem('cart', JSON.stringify(this.cart));
    },
    
    getTotal() {
        return this.cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    }
}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Récapitulatif de la commande -->
    <div class="lg:col-span-2">
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Récapitulatif de votre commande</h2>
            
            <div x-show="cart.length === 0" class="text-center py-8">
                <p class="text-gray-500 mb-4">Votre panier est vide</p>
                <a href="{{ route('client.menu.index') }}" class="btn btn-primary">
                    Voir le menu
                </a>
            </div>

            <div x-show="cart.length > 0">
                <div class="divide-y">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="py-4 flex justify-between items-center">
                            <div class="flex-1">
                                <h3 class="font-medium" x-text="item.nom"></h3>
                                <p class="text-sm text-gray-600">
                                    <span x-text="Number(item.prix).toFixed(2)"></span> € × 
                                    <span x-text="item.quantite"></span>
                                </p>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2">
                                    <button 
                                        @click="updateQuantity(index, -1)" 
                                        class="btn btn-sm bg-gray-200 hover:bg-gray-300 px-2 py-1"
                                    >
                                        -
                                    </button>
                                    <span class="font-medium w-8 text-center" x-text="item.quantite"></span>
                                    <button 
                                        @click="updateQuantity(index, 1)" 
                                        class="btn btn-sm bg-gray-200 hover:bg-gray-300 px-2 py-1"
                                    >
                                        +
                                    </button>
                                </div>
                                
                                <p class="font-bold text-primary-600 w-20 text-right">
                                    <span x-text="(item.prix * item.quantite).toFixed(2)"></span> €
                                </p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span>Total:</span>
                        <span class="text-primary-600">
                            <span x-text="getTotal().toFixed(2)"></span> €
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de commande -->
    <div>
        <form method="POST" action="{{ route('client.commandes.store') }}" x-show="cart.length > 0">
            @csrf

            <div class="card mb-4">
                <h2 class="text-xl font-semibold mb-4">Informations</h2>

                <!-- Sélection de la table -->
                <div class="mb-4">
                    <label for="idTable" class="label">Table *</label>
                    <select 
                        id="idTable" 
                        name="idTable" 
                        x-model="selectedTable"
                        class="select @error('idTable') border-red-500 @enderror" 
                        required
                    >
                        <option value="">Sélectionnez une table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->idTable }}">
                                Table {{ $table->numero }} (Capacité: {{ $table->capacite ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    @error('idTable')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    @if($tables->isEmpty())
                        <p class="text-orange-600 text-sm mt-2">
                            Aucune table disponible pour le moment. Veuillez réessayer plus tard ou faire une réservation.
                        </p>
                    @endif
                </div>

                <!-- Champs cachés pour les plats et boissons -->
                <template x-for="(item, index) in cart" :key="index">
                    <div>
                        <input 
                            type="hidden" 
                            :name="item.type === 'plat' ? `plats[${index}][id]` : `boissons[${index}][id]`" 
                            :value="item.id"
                        >
                        <input 
                            type="hidden" 
                            :name="item.type === 'plat' ? `plats[${index}][quantite]` : `boissons[${index}][quantite]`" 
                            :value="item.quantite"
                        >
                    </div>
                </template>
            </div>

            <!-- Boutons d'action -->
            <div class="space-y-3">
                <button 
                    type="submit" 
                    class="btn btn-primary w-full"
                    :disabled="!selectedTable || cart.length === 0"
                >
                    Valider la commande
                </button>
                
                <a href="{{ route('client.menu.index') }}" class="btn btn-outline w-full block text-center">
                    Retour au menu
                </a>
            </div>
        </form>

        <!-- Simulation de paiement (si nécessaire)-->
        <div class="card bg-blue-50 border-blue-200 mt-4" x-show="cart.length > 0">
            <h3 class="font-semibold text-blue-900 mb-2">ℹ️ Information</h3>
            <p class="text-sm text-blue-800">
                Le paiement sera effectué au moment du service. Vous recevrez votre ticket après validation de la commande.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Nettoyer le panier après soumission réussie (si redirection)
    @if(session('success'))
        sessionStorage.removeItem('cart');
    @endif
</script>
@endpush
