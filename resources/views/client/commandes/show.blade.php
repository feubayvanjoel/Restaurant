@extends('layouts.app')

@section('title', 'Détails Commande #' . $commande->idCommande)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $commande->idCommande }}</h1>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('client.commandes.index') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails de la commande -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        <span class="badge 
                            @if($commande->statut == 'En attente') badge-warning
                            @elseif($commande->statut == 'En préparation') badge-info
                            @elseif($commande->statut == 'Préparée') badge-primary
                            @elseif($commande->statut == 'Servie') badge-success
                            @elseif($commande->statut == 'Terminée') badge-dark
                            @elseif($commande->statut == 'En attente paiement') badge-warning
                            @elseif($commande->statut == 'Payée') badge-success
                            @elseif($commande->statut == 'Annulee') badge-danger
                            @endif
                        ">
                            {{ $commande->statut }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Table</dt>
                    <dd class="mt-1 text-gray-900">Table {{ $commande->table->numero ?? 'N/A' }} ({{ $commande->table->capacite ?? '?' }} pers.)</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de commande</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Numéro de ticket</dt>
                    <dd class="mt-1 text-gray-900">#{{ $commande->ticket->idTicket ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Articles commandés -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Articles</h2>
            
            <div class="divide-y">
                <!-- Plats -->
                @foreach($commande->composer as $composer)
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $composer->plat->nom }}</h3>
                            <p class="text-sm text-gray-600">Plat • Quantité: {{ $composer->quantite }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            {{ number_format($composer->plat->prix * $composer->quantite, 2) }} €
                        </p>
                    </div>
                @endforeach

                <!-- Boissons -->
                @foreach($commande->contenir as $contenir)
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $contenir->boisson->nom }}</h3>
                            <p class="text-sm text-gray-600">Boisson • Quantité: {{ $contenir->quantite }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            {{ number_format($contenir->boisson->prix * $contenir->quantite, 2) }} €
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold">Total</span>
                    <span class="text-2xl font-bold text-primary-600">
                        {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions et informations supplémentaires -->
    <div class="space-y-4">
        <!-- Actions -->
        <div class="card">
            <h3 class="font-semibold mb-3">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('client.commandes.ticket', $commande) }}" 
                   class="btn btn-primary w-full"
                   target="_blank">
                    📄 Télécharger le ticket
                </a>

                @if($commande->statut === 'En attente')
                    <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger w-full">
                            ❌ Annuler la commande
                        </button>
                    </form>
                @endif

                @if($commande->statut === 'Servie' || $commande->statut === 'Terminée')
                    <div x-data="{ 
                        paymentModalOpen: false, 
                        paymentMethod: null, 
                        cardNumber: '', 
                        simulating: false 
                    }">
                        <button @click="paymentModalOpen = true" class="w-full py-3 px-4 bg-green-50 border-2 border-green-500 text-green-700 font-bold rounded hover:bg-green-100 transition flex items-center justify-center gap-2">
                            <span>✅</span> J'ai terminé mon repas
                        </button>

                        <!-- Payment Modal -->
                        <div x-show="paymentModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
                            <div class="fixed inset-0 bg-black bg-opacity-50" @click="paymentModalOpen = false"></div>
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
                                    <button @click="paymentModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">✕</button>
                                    
                                    <h3 class="text-xl font-bold mb-4 text-center">Paiement de la commande</h3>
                                    <p class="text-center text-gray-600 mb-6">Total à régler : <span class="font-bold text-lg text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</span></p>

                                    <!-- Selection -->
                                    <div x-show="!paymentMethod" class="space-y-3">
                                        <button @click="paymentMethod = 'card'" class="w-full p-4 border rounded hover:bg-blue-50 hover:border-blue-500 flex items-center justify-between transition">
                                            <span class="font-semibold">💳 Payer par Carte</span>
                                            <span class="text-gray-400">→</span>
                                        </button>
                                        <button @click="paymentMethod = 'cash'" class="w-full p-4 border rounded hover:bg-green-50 hover:border-green-500 flex items-center justify-between transition">
                                            <span class="font-semibold">💵 Payer en Espèces</span>
                                            <span class="text-gray-400">→</span>
                                        </button>
                                    </div>

                                    <!-- Card Flow -->
                                    <div x-show="paymentMethod === 'card'" class="space-y-4">
                                        <form action="{{ route('client.commandes.pay-card', $commande) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte (Simulation)</label>
                                                <input type="text" name="card_number" x-model="cardNumber" class="w-full p-2 border rounded" placeholder="XXXX XXXX XXXX XXXX" required>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="button" @click="paymentMethod = null" class="btn btn-outline flex-1">Retour</button>
                                                <button type="submit" class="btn btn-primary flex-1">Valider le paiement</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Cash Flow -->
                                    <div x-show="paymentMethod === 'cash'" class="space-y-4 text-center">
                                        <p class="text-gray-600">Pour payer en espèces, veuillez vous rendre à la caisse ou attendre qu'un serveur vienne vous voir.</p>
                                        <div class="bg-yellow-50 p-3 rounded text-yellow-800 text-sm mb-4">
                                            ⚠️ Le caissier devra valider votre paiement manuellement.
                                        </div>
                                        <form action="{{ route('client.commandes.pay-cash-request', $commande) }}" method="POST">
                                            @csrf
                                            <div class="flex gap-2">
                                                <button type="button" @click="paymentMethod = null" class="btn btn-outline flex-1">Retour</button>
                                                <button type="submit" class="btn btn-success flex-1" style="background-color: #10B981; color: white;">Demander validation</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- État de la commande -->
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">État de la commande</h3>
            <div class="space-y-3">
                <!-- Etape 1 : Reçue -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ in_array($commande->statut, ['En attente', 'En préparation', 'Préparée', 'Servie', 'Terminée', 'En attente paiement', 'Payée']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Commande reçue</p>
                    </div>
                </div>

                <!-- Etape 2 : En préparation -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ in_array($commande->statut, ['En préparation', 'Préparée', 'Servie', 'Terminée', 'En attente paiement', 'Payée']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                            @if(in_array($commande->statut, ['En préparation', 'Préparée', 'Servie', 'Terminée', 'En attente paiement', 'Payée']))
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">En préparation</p>
                    </div>
                </div>

                <!-- Etape 3 : Prête / Servie -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ in_array($commande->statut, ['Servie', 'Terminée', 'En attente paiement', 'Payée']) ? 'bg-green-500' : (in_array($commande->statut, ['Préparée']) ? 'bg-blue-500' : 'bg-gray-300') }} flex items-center justify-center">
                            @if(in_array($commande->statut, ['Servie', 'Terminée', 'En attente paiement', 'Payée']))
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($commande->statut == 'Préparée')
                                <span class="text-white text-xs">...</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Servie</p>
                        @if($commande->statut == 'Préparée')
                            <p class="text-xs text-blue-600">En attente du serveur...</p>
                        @endif
                    </div>
                </div>

                <!-- Etape 4 : Terminée -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ in_array($commande->statut, ['Terminée', 'En attente paiement', 'Payée']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                            @if(in_array($commande->statut, ['Terminée', 'En attente paiement', 'Payée']))
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Terminée</p>
                    </div>
                </div>

                <!-- Etape 5 : Payée -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full {{ $commande->statut == 'Payée' ? 'bg-green-500' : ($commande->statut == 'En attente paiement' ? 'bg-orange-500' : 'bg-gray-300') }} flex items-center justify-center">
                            @if($commande->statut == 'Payée')
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($commande->statut == 'En attente paiement')
                                <span class="text-white text-xs">...</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Payée</p>
                        @if($commande->statut == 'En attente paiement')
                            <p class="text-xs text-orange-600">Validation caisse...</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/client-auto-refresh.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshUrl = "{{ route('client.api.commandes.refresh-details', $commande) }}";
    
    function get(obj, key) {
        if (!obj) return null;
        return obj[key] || obj[key.toUpperCase()] || null;
    }
    
    function updateCommandeDetails(data) {
        const commande = data.commande;
        if (!commande) return;
        
        const scrollTop = window.scrollY;
        updateStatus(commande);
        updateProgressSteps(commande);
        window.scrollTo(0, scrollTop);
    }
    
    function updateStatus(commande) {
        const statut = get(commande, 'statut') || get(commande, 'STATUT');
        const statusBadge = document.querySelector('.badge');
        
        if (statusBadge && statusBadge.textContent.trim() !== statut) {
            statusBadge.className = 'badge ' + getBadgeClass(statut);
            statusBadge.textContent = statut;
        }
    }
    
    function updateProgressSteps(commande) {
        const statut = get(commande, 'statut') || get(commande, 'STATUT');
        
        const steps = [
            { selector: '.flex.items-start:nth-child(1) .h-8.w-8', active: ['En attente', 'En préparation', 'Préparée', 'Servie', 'Terminée', 'En attente paiement', 'Payée'].includes(statut) },
            { selector: '.flex.items-start:nth-child(2) .h-8.w-8', active: ['En préparation', 'Préparée', 'Servie', 'Terminée', 'En attente paiement', 'Payée'].includes(statut) },
            { selector: '.flex.items-start:nth-child(3) .h-8.w-8', active: ['Servie', 'Terminée', 'En attente paiement', 'Payée'].includes(statut), preparing: statut === 'Préparée' },
            { selector: '.flex.items-start:nth-child(4) .h-8.w-8', active: ['Terminée', 'En attente paiement', 'Payée'].includes(statut) },
            { selector: '.flex.items-start:nth-child(5) .h-8.w-8', active: statut === 'Payée', preparing: statut === 'En attente paiement' }
        ];
        
        steps.forEach(step => {
            const element = document.querySelector(step.selector);
            if (!element) return;
            
            element.className = element.className.replace(/bg-green-500|bg-blue-500|bg-orange-500|bg-gray-300/g, '').trim();
            
            if (step.active) {
                element.className += ' bg-green-500';
            } else if (step.preparing) {
                element.className += ' ' + (step.selector.includes(':nth-child(3)') ? 'bg-blue-500' : 'bg-orange-500');
            } else {
                element.className += ' bg-gray-300';
            }
        });
    }
    
    function getBadgeClass(statut) {
        const badges = {
            'En attente': 'badge-warning',
            'En préparation': 'badge-info',
            'Préparée': 'badge-primary',
            'Servie': 'badge-success',
            'Terminée': 'badge-dark',
            'En attente paiement': 'badge-warning',
            'Payée': 'badge-success',
            'Annulee': 'badge-danger'
        };
        return badges[statut] || 'badge-secondary';
    }
    
    const autoRefresh = new ClientAutoRefresh(refreshUrl, updateCommandeDetails, 2000);
    autoRefresh.start();
    window.addEventListener('beforeunload', () => autoRefresh.stop());
});
</script>
@endpush
