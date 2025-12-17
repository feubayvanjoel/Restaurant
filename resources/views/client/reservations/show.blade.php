@extends('layouts.app')

@section('title', 'Détails Réservation #' . $reservation->idHoraireReservation)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Réservation #{{ $reservation->idHoraireReservation }}</h1>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('client.reservations.index') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Détails de la réservation -->
    <div class="lg:col-span-2">
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations</h2>
            
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        <span class="badge 
                            @if($reservation->statut == 'ACTIVE') badge-success
                            @elseif($reservation->statut == 'TERNINEE') badge-info
                            @elseif($reservation->statut == 'ANNULEE') badge-danger
                            @else badge-warning
                            @endif
                        ">
                            {{ $reservation->statut }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Table</dt>
                    <dd class="mt-1 text-gray-900 font-semibold">
                        Table {{ $reservation->table?->numero ?? 'N/A' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Nombre de personnes</dt>
                    <dd class="mt-1 text-gray-900">{{ $reservation->nombre_personne }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Capacité de la table</dt>
                    <dd class="mt-1 text-gray-900">{{ $reservation->table->capacite }} personnes</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Date et heure de début</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H:i') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Date et heure de fin</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y à H:i') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Durée</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ round((strtotime($reservation->date_fin) - strtotime($reservation->date_debut)) / 60) }} minutes
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ \Carbon\Carbon::parse($reservation->creer_le)->format('d/m/Y à H:i') }}
                    </dd>
                </div>
            </dl>

            <!-- Compte à rebours si actif et futur -->
            @if($reservation->statut === 'ACTIVE' && \Carbon\Carbon::parse($reservation->echeance)->isFuture())
                <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded">
                    <div x-data="countdown('{{ $reservation->echeance }}')">
                        <h3 class="font-semibold text-orange-900 mb-2">⏱️ Temps restant</h3>
                        <p class="text-2xl font-mono font-bold text-orange-600" x-text="remaining"></p>
                        <p class="text-sm text-orange-700 mt-1">
                            Échéance: {{ \Carbon\Carbon::parse($reservation->echeance)->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions et informations -->
    <div class="space-y-4">
        <!-- Actions -->
        @if($reservation->statut === 'ACTIVE')
            <div class="card">
                <h3 class="font-semibold mb-3">Actions</h3>
                <form method="POST" action="{{ route('client.reservations.cancel', $reservation) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                    @csrf
                    <button type="submit" class="btn btn-danger w-full">
                        ❌ Annuler la réservation
                    </button>
                </form>
            </div>
        @endif

        <!-- Informations de la table -->
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">📍 Informations de la table</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-blue-700">Numéro</dt>
                    <dd class="text-blue-900 font-semibold">Table {{ $reservation->table?->numero ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-blue-700">Capacité</dt>
                    <dd class="text-blue-900">{{ $reservation->table->capacite }} personnes</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-blue-700">Statut actuel</dt>
                    <dd>
                        <span class="badge 
                            @if($reservation->table->statut == 'Libre') badge-success
                            @else badge-warning
                            @endif
                        ">
                            {{ $reservation->table->statut }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Rappel -->
        <div class="card bg-green-50 border-green-200">
            <h3 class="font-semibold text-green-900 mb-2">✅ Rappel</h3>
            <ul class="text-sm text-green-800 space-y-1">
                <li>• Arrivez à l'heure</li>
                <li>• Durée maximale: 2h30</li>
                <li>• Passez votre commande sur place</li>
            </ul>
        </div>
    </div>
</div>
@endsection
