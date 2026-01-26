@extends('layouts.app')

@section('title', 'Mon Espace')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Bienvenue, {{ $client->prenom }} !</h1>
    <p class="text-gray-600">Gérez vos commandes et réservations</p>
</div>

<!-- Onglets -->
<div x-data="{ tab: 'commandes' }" class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="tab = 'commandes'" 
                :class="tab === 'commandes' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Mes Commandes ({{ $commandesEnCours->count() }})
            </button>
            <button 
                @click="tab = 'reservations'" 
                :class="tab === 'reservations' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Mes Réservations ({{ $reservationsEnCours->count() }})
            </button>
            <button 
                @click="tab = 'historique'" 
                :class="tab === 'historique' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                Historique
            </button>
        </nav>
    </div>

    <!-- Contenu des onglets -->
    <div class="mt-6">
        <!-- Onglet Commandes en cours -->
        <div x-show="tab === 'commandes'">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Commandes en cours</h2>
                <div class="space-x-2">
                    <a href="{{ route('client.menu.index') }}" class="btn btn-outline">Voir le menu</a>
                    <a href="{{ route('client.commandes.create') }}" class="btn btn-primary">Nouvelle commande</a>
                </div>
            </div>

            @if($commandesEnCours->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Vous n'avez pas de commandes en cours.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($commandesEnCours as $commande)
                        <div class="card">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold">Commande #{{ $commande->idCommande }}</h3>
                                <span class="badge 
                                    @if($commande->statut == 'En attente') badge-warning
                                    @elseif($commande->statut == 'En cours') badge-info
                                    @else badge-success
                                    @endif
                                ">
                                    {{ $commande->statut }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Table:</strong> {{ $commande->table->numero ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-lg font-bold text-primary-600 mb-3">
                                {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                            </p>
                            <a href="{{ route('client.commandes.show', ['commande' => $commande->getKey()]) }}" class="btn btn-primary btn-sm w-full">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Onglet Réservations en cours -->
        <div x-show="tab === 'reservations'">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Réservations actives</h2>
                <a href="{{ route('client.reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
            </div>

            @if($reservationsEnCours->isEmpty())
                <div class="card">
                    <p class="text-gray-500 text-center py-8">Vous n'avez pas de réservations.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($reservationsEnCours as $reservation)
                        <div class="card">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold">Réservation #{{ $reservation->idHoraireReservation }}</h3>
                                <span class="badge badge-success">{{ $reservation->statut }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Table:</strong> {{ $reservation->table?->numero ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Personnes:</strong> {{ $reservation->nombre_personne }}
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('client.reservations.show', ['reservation' => $reservation->getKey()]) }}" class="btn btn-primary btn-sm w-full">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Onglet Historique -->
        <div x-show="tab === 'historique'">
            <h2 class="text-xl font-semibold mb-4">Historique</h2>
            
            <!-- Historique des commandes -->
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-3">Commandes terminées</h3>
                @if($historiqueCommandes->isEmpty())
                    <p class="text-gray-500">Aucune commande terminée.</p>
                @else
                    <div class="space-y-3">
                        @foreach($historiqueCommandes as $commande)
                            <div class="card">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Commande #{{ $commande->idCommande }}</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary-600">{{ number_format($commande->ticket->prix ?? 0, 2) }} €</p>
                                        <a href="{{ route('client.commandes.show', ['commande' => $commande->getKey()]) }}" class="text-sm text-primary-600 hover:underline">Détails</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Historique des réservations -->
            <div>
                <h3 class="text-lg font-medium mb-3">Réservations passées</h3>
                @if($historiqueReservations->isEmpty())
                    <p class="text-gray-500">Aucune réservation passée.</p>
                @else
                    <div class="space-y-3">
                        @foreach($historiqueReservations as $reservation)
                            <div class="card">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Réservation #{{ $reservation->idHoraireReservation }}</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-info">{{ $reservation->statut }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/client-auto-refresh.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshUrl = "{{ route('client.api.dashboard.refresh') }}";
    let currentTab = 'commandes'; // Tab par défaut
    
    // Helper pour accéder aux propriétés en camelCase ou UPPERCASE
    function get(obj, key) {
        if (!obj) return null;
        // Essayer camelCase puis UPPERCASE
        return obj[key] || obj[key.toUpperCase()] || null;
    }
    
    // Écouter les changements d'onglet avec Alpine.js
    document.addEventListener('alpine:init', () => {
        Alpine.store('dashboardTab', {
            current: 'commandes',
            set(tab) {
                this.current = tab;
                currentTab = tab;
            }
        });
    });
    
    // Fonction de mise à jour des données
    function updateDashboard(data) {
        // Sauvegarder la position de scroll
        const scrollTop = window.scrollY;
        const scrollLeft = window.scrollX;
        
        // Mettre à jour les compteurs d'onglets
        updateTabCounts(data);
        
        // Mettre à jour les cartes de commandes
        updateCommandesCards(data.commandesEnCours);
        
        // Mettre à jour les cartes de réservations
        updateReservationsCards(data.reservationsEnCours);
        
        // Mettre à jour l'historique
        updateHistorique(data.historiqueCommandes, data.historiqueReservations);
        
        // Restaurer la position de scroll (silencieux)
        window.scrollTo(scrollLeft, scrollTop);
    }
    
    function updateTabCounts(data) {
        const commandesCount = data.commandesEnCours.length;
        const reservationsCount = data.reservationsEnCours.length;
        
        // Mise à jour du bouton "Mes Commandes"
        const commandesTab = document.querySelector('button[\\@click*="commandes"]');
        if (commandesTab) {
            commandesTab.innerHTML = `Mes Commandes (${commandesCount})`;
        }
        
        // Mise à jour du bouton "Mes Réservations"
        const reservationsTab = document.querySelector('button[\\@click*="reservations"]');
        if (reservationsTab) {
            reservationsTab.innerHTML = `Mes Réservations (${reservationsCount})`;
        }
    }
    
    function updateCommandesCards(commandes) {
        const container = document.querySelector('[x-show="tab === \'commandes\'"] .grid');
        if (!container) return;
        
        if (commandes.length === 0) {
            const emptyState = `
                <div class="col-span-full">
                    <div class="card">
                        <p class="text-gray-500 text-center py-8">Vous n'avez pas de commandes en cours.</p>
                    </div>
                </div>
            `;
            if (container.innerHTML.trim() !== emptyState.trim()) {
                container.innerHTML = emptyState;
            }
            return;
        }
        
        // Générer le HTML des cartes
        let html = '';
        commandes.forEach(commande => {
            html += generateCommandeCard(commande);
        });
        
        // Mettre à jour uniquement si le contenu a changé
        if (!DOMUtils.areEquivalent(container.innerHTML, html)) {
            const scrollTop = window.scrollY;
            container.innerHTML = html;
            window.scrollTo(0, scrollTop);
        }
    }
    
    function generateCommandeCard(commande) {
        const idCommande = get(commande, 'idCommande') || get(commande, 'IDCOMMANDE');
        const statut = get(commande, 'statut') || get(commande, 'STATUT');
        const horaire = get(commande, 'horaire') || get(commande, 'HORAIRE');
        const ticket = get(commande, 'ticket');
        const table = get(commande, 'table');
        
        const badgeClass = getBadgeClass(statut);
        const prix = (ticket ? (get(ticket, 'prix') || get(ticket, 'PRIX')) : null) || 0;
        const tableNum = (table ? (get(table, 'numero') || get(table, 'NUMERO')) : null) || 'N/A';
        const date = new Date(horaire).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="card">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold">Commande #${idCommande}</h3>
                    <span class="badge ${badgeClass}">${statut}</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>Table:</strong> ${tableNum}
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    <strong>Date:</strong> ${date}
                </p>
                <p class="text-lg font-bold text-primary-600 mb-3">
                    ${parseFloat(prix).toFixed(2)} €
                </p>
                <a href="/client/commandes/${idCommande}" class="btn btn-primary btn-sm w-full">
                    Voir les détails
                </a>
            </div>
        `;
    }
    
    function updateReservationsCards(reservations) {
        const container = document.querySelector('[x-show="tab === \'reservations\'"] .grid');
        if (!container) return;
        
        if (reservations.length === 0) {
            const emptyState = `
                <div class="col-span-full">
                    <div class="card">
                        <p class="text-gray-500 text-center py-8">Vous n'avez pas de réservations.</p>
                    </div>
                </div>
            `;
            if (container.innerHTML.trim() !== emptyState.trim()) {
                container.innerHTML = emptyState;
            }
            return;
        }
        
        let html = '';
        reservations.forEach(reservation => {
            html += generateReservationCard(reservation);
        });
        
        if (!DOMUtils.areEquivalent(container.innerHTML, html)) {
            const scrollTop = window.scrollY;
            container.innerHTML = html;
            window.scrollTo(0, scrollTop);
        }
    }
    
    function generateReservationCard(reservation) {
        const idReservation = get(reservation, 'idHoraireReservation') || get(reservation, 'IDHORAIRERESERVATION');
        const statut = get(reservation, 'statut') || get(reservation, 'STATUT');
        const nombrePersonne = get(reservation, 'nombre_personne') || get(reservation, 'NOMBRE_PERSONNE');
        const dateDebut = get(reservation, 'date_debut') || get(reservation, 'DATE_DEBUT');
        const table = get(reservation, 'table');
        
        const tableNum = (table ? (get(table, 'numero') || get(table, 'NUMERO')) : null) || 'N/A';
        const dateDebutFormatted = new Date(dateDebut).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="card">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold">Réservation #${idReservation}</h3>
                    <span class="badge badge-success">${statut}</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>Table:</strong> ${tableNum}
                </p>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>Personnes:</strong> ${nombrePersonne}
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    <strong>Date:</strong> ${dateDebutFormatted}
                </p>
                <a href="/client/reservations/${idReservation}" class="btn btn-primary btn-sm w-full">
                    Voir les détails
                </a>
            </div>
        `;
    }
    
    function updateHistorique(commandes, reservations) {
        // Mettre à jour l'historique des commandes
        const commandesContainer = document.querySelector('[x-show="tab === \'historique\'"] .space-y-3:first-of-type');
        if (commandesContainer && commandes.length > 0) {
            let html = '';
            commandes.forEach(commande => {
                const idCommande = get(commande, 'idCommande') || get(commande, 'IDCOMMANDE');
                const horaire = get(commande, 'horaire') || get(commande, 'HORAIRE');
                const ticket = get(commande, 'ticket');
                const prix = (ticket ? (get(ticket, 'prix') || get(ticket, 'PRIX')) : null) || 0;
                const date = new Date(horaire).toLocaleString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                html += `
                    <div class="card">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">Commande #${idCommande}</p>
                                <p class="text-sm text-gray-600">${date}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-primary-600">${parseFloat(prix).toFixed(2)} €</p>
                                <a href="/client/commandes/${idCommande}" class="text-sm text-primary-600 hover:underline">Détails</a>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (!DOMUtils.areEquivalent(commandesContainer.innerHTML, html)) {
                const scrollTop = window.scrollY;
                commandesContainer.innerHTML = html;
                window.scrollTo(0, scrollTop);
            }
        }
    }
    
    function getBadgeClass(statut) {
        const badges = {
            'En attente': 'badge-warning',
            'En cours': 'badge-info',
            'En préparation': 'badge-info',
            'Préparée': 'badge-success',
            'Servie': 'badge-success'
        };
        return badges[statut] || 'badge-secondary';
    }
    
    // Démarrer le rafraîchissement automatique
    const autoRefresh = new ClientAutoRefresh(refreshUrl, updateDashboard, 2000);
    autoRefresh.start();
    
    // Arrêter lors du déchargement de la page
    window.addEventListener('beforeunload', () => autoRefresh.stop());
});
</script>
@endpush
@endsection
