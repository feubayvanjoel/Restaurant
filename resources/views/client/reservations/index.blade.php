@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mes Réservations</h1>
            <p class="text-gray-600">Consultez toutes vos réservations</p>
        </div>
        <a href="{{ route('client.reservations.create') }}" class="btn btn-primary">
            Nouvelle réservation
        </a>
    </div>
</div>

@if($reservations->isEmpty())
    <div class="card">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune réservation</h3>
            <p class="text-gray-600 mb-4">Vous n'avez pas encore fait de réservation.</p>
            <a href="{{ route('client.reservations.create') }}" class="btn btn-primary">
                Faire une réservation
            </a>
        </div>
    </div>
@else
    <div class="space-y-4">
        @foreach($reservations as $reservation)
            <div class="card hover:shadow-lg transition-shadow">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <!-- Informations de la réservation -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-xl font-semibold">Réservation #{{ $reservation->idHoraireReservation }}</h3>
                            <span class="badge 
                                @if($reservation->statut == 'ACTIVE') badge-success
                                @elseif($reservation->statut == 'TERNINEE') badge-info
                                @elseif($reservation->statut == 'ANNULEE') badge-danger
                                @else badge-warning
                                @endif
                            ">
                                {{ $reservation->statut }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Table:</span> {{ $reservation->table?->numero ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Personnes:</span> {{ $reservation->nombre_personne }}
                            </div>
                            <div>
                                <span class="font-medium">Début:</span> {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">Fin:</span> {{ \Carbon\Carbon::parse($reservation->date_fin)->format('H:i') }}
                            </div>
                        </div>

                        <!-- Compte à rebours si actif -->
                        @if($reservation->statut === 'ACTIVE' && \Carbon\Carbon::parse($reservation->echeance)->isFuture())
                            <div class="mt-2">
                                <div x-data="countdown('{{ $reservation->echeance }}')" class="text-sm">
                                    <span class="font-medium text-orange-600">⏱️ Temps restant:</span>
                                    <span x-text="remaining" class="font-mono"></span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                        <a href="{{ route('client.reservations.show', $reservation) }}" class="btn btn-primary btn-sm">
                            Voir les détails
                        </a>
                         
                        @if($reservation->statut === 'ACTIVE')
                            <form method="POST" action="{{ route('client.reservations.cancel', $reservation) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm w-full">
                                    Annuler
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
