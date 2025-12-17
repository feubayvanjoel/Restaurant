@extends('layouts.app')

@section('title', 'Encaisser Commande #' . $commande->idCommande)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Encaisser Commande #{{ $commande->idCommande }}</h1>
            <p class="text-gray-600">Traitement du paiement</p>
        </div>
        <a href="{{ route('caissier.encaissements.index') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-600">Table</dt>
                    <dd class="text-2xl font-bold text-primary-600">{{ $commande->table->numero ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Client</dt>
                    <dd class="font-medium">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Date/Heure</dt>
                    <dd>{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Ticket</dt>
                    <dd class="font-medium">#{{ $commande->ticket->idTicket ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Détails de la commande</h2>
            
            <div class="space-y-3">
                @foreach($commande->composer as $composer)
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-bold">{{ $composer->plat->nom }}</p>
                            <p class="text-sm text-gray-600">{{ number_format($composer->plat->prix, 2) }} € × {{ $composer->quantite }}</p>
                        </div>
    <p class="font-bold text-primary-600">{{ number_format($composer->plat->prix * $composer->quantite, 2) }} €</p>
                    </div>
                @endforeach

                @foreach($commande->contenir as $contenir)
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-bold">🥤 {{ $contenir->boisson->nom }}</p>
                            <p class="text-sm text-gray-600">{{ number_format($contenir->boisson->prix, 2) }} € × {{ $contenir->quantite }}</p>
                        </div>
                        <p class="font-bold text-primary-600">{{ number_format($contenir->boisson->prix * $contenir->quantite, 2) }} €</p>
                    </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold">TOTAL</span>
                    <span class="text-3xl font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de paiement -->
    <div>
        <form method="POST" action="{{ route('caissier.encaissements.process', $commande) }}" x-data="{ modePaiement: 'Carte' }">
            @csrf

            <div class="card mb-4">
                <h3 class="font-semibold mb-4">Mode de Paiement</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-gray-50"
                           :class="modePaiement === 'Carte' ? 'border-primary-500 bg-primary-50' : 'border-gray-300'">
                        <input type="radio" name="mode_paiement" value="Carte" x-model="modePaiement" class="mr-3" required>
                        <div class="flex-1">
                            <p class="font-semibold">💳 Carte Bancaire</p>
                            <p class="text-xs text-gray-600">Visa, Mastercard, etc.</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-gray-50"
                           :class="modePaiement === 'Especes' ? 'border-primary-500 bg-primary-50' : 'border-gray-300'">
                        <input type="radio" name="mode_paiement" value="Especes" x-model="modePaiement" class="mr-3">
                        <div class="flex-1">
                            <p class="font-semibold">💵 Espèces</p>
                            <p class="text-xs text-gray-600">Paiement en liquide</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-gray-50"
                           :class="modePaiement === 'Mobile' ? 'border-primary-500 bg-primary-50' : 'border-gray-300'">
                        <input type="radio" name="mode_paiement" value="Mobile" x-model="modePaiement" class="mr-3">
                        <div class="flex-1">
                            <p class="font-semibold">📱 Paiement Mobile</p>
                            <p class="text-xs text-gray-600">Apple Pay, Google Pay, etc.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="card bg-primary-600 text-white">
                <p class="text-sm text-primary-100 mb-2">Montant à encaisser</p>
                <p class="text-4xl font-bold mb-4">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</p>
                
                <button type="submit" class="btn w-full bg-white text-primary-600 hover:bg-gray-100">
                    ✅ Valider le paiement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
