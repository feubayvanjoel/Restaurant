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
                                <span class="text-xs text-gray-500">({{ $reservation->table?->capacite ?? '?' }} pers.)</span>
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

@push('scripts')
<script src="{{ asset('js/client-auto-refresh.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshUrl = "{{ route('client.api.reservations.refresh') }}";
    
    // Helper pour accéder aux propriétés en camelCase ou UPPERCASE
    function get(obj, key) {
        if (!obj) return null;
        return obj[key] || obj[key.toUpperCase()] || null;
    }
    
    function updateReservationsList(data) {
        const container = document.querySelector('.space-y-4');
        if (!container) return;
        
        const scrollTop = window.scrollY;
        
        if (data.reservations.length === 0) {
            container.innerHTML = `
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
            `;
        } else {
            let html = '';
            data.reservations.forEach(reservation => {
                const idReservation = get(reservation, 'idHoraireReservation') || get(reservation, 'IDHORAIRERESERVATION');
                const statut = get(reservation, 'statut') || get(reservation, 'STATUT');
                const nombrePersonne = get(reservation, 'nombre_personne') || get(reservation, 'NOMBRE_PERSONNE');
                const dateDebut = get(reservation, 'date_debut') || get(reservation, 'DATE_DEBUT');
                const dateFin = get(reservation, 'date_fin') || get(reservation, 'DATE_FIN');
                const table = get(reservation, 'table');
                
                const badgeClass = getReservationBadgeClass(statut);
                const tableNum = (table ? (get(table, 'numero') || get(table, 'NUMERO')) : null) || 'N/A';
                const capacite = (table ? (get(table, 'capacite') || get(table, 'CAPACITE')) : null) || '?';
                const dateDebutFormatted = new Date(dateDebut).toLocaleString('fr-FR', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
                const heureFin = new Date(dateFin).toLocaleTimeString('fr-FR', {
                    hour: '2-digit', minute: '2-digit'
                });
                
                const showCancelButton = statut === 'ACTIVE' ? `
                    <form method="POST" action="/client/reservations/${idReservation}/cancel" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-full">
                            Annuler
                        </button>
                    </form>
                ` : '';
                
                html += `
                    <div class="card hover:shadow-lg transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-semibold">Réservation #${idReservation}</h3>
                                    <span class="badge ${badgeClass}">${statut}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Table:</span> ${tableNum}
                                        <span class="text-xs text-gray-500">(${capacite} pers.)</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Personnes:</span> ${nombrePersonne}
                                    </div>
                                    <div>
                                        <span class="font-medium">Début:</span> ${dateDebutFormatted}
                                    </div>
                                    <div>
                                        <span class="font-medium">Fin:</span> ${heureFin}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                                <a href="/client/reservations/${idReservation}" class="btn btn-primary btn-sm">
                                    Voir les détails
                                </a>
                                ${showCancelButton}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (!DOMUtils.areEquivalent(container.innerHTML, html)) {
                container.innerHTML = html;
            }
        }
        
        window.scrollTo(0, scrollTop);
    }
    
    function getReservationBadgeClass(statut) {
        const badges = {
            'ACTIVE': 'badge-success',
            'TERNINEE': 'badge-info',
            'ANNULEE': 'badge-danger'
        };
        return badges[statut] || 'badge-warning';
    }
    
    const autoRefresh = new ClientAutoRefresh(refreshUrl, updateReservationsList, 2000);
    autoRefresh.start();
    
    window.addEventListener('beforeunload', () => autoRefresh.stop());
});
</script>
@endpush
@endsection
