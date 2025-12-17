@extends('layouts.app')

@section('title', 'Rapports et Statistiques')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rapports et Statistiques</h1>
            <p class="text-gray-600">Analysez les performances du restaurant</p>
        </div>
        
        <!-- Sélecteur de période -->
        <select class="select" onchange="window.location.href='?periode=' + this.value">
            <option value="7days" {{ $periode === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $periode === '30days' ? 'selected' : '' }}>30 derniers jours</option>
            <option value="3months" {{ $periode === '3months' ? 'selected' : '' }}>3 derniers mois</option>
            <option value="year" {{ $periode === 'year' ? 'selected' : '' }}>Cette année</option>
        </select>
    </div>
</div>

<!-- Cartes de statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Chiffre d'affaires -->
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Chiffre d'Affaires</p>
        <p class="text-3xl font-bold">{{ number_format($ca['total'], 0) }} €</p>
        <p class="text-xs text-green-100 mt-1">{{ $ca['nombre'] }} tickets</p>
    </div>

    <!-- Ticket moyen -->
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Ticket Moyen</p>
        <p class="text-3xl font-bold">{{ number_format($ca['moyenne'], 2) }} €</p>
        <p class="text-xs text-blue-100 mt-1">Par commande</p>
    </div>

    <!-- Commandes -->
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Total Commandes</p>
        <p class="text-3xl font-bold">{{ $commandes['total'] }}</p>
        <p class="text-xs text-purple-100 mt-1">{{ $commandes['terminees'] }} terminées</p>
    </div>

    <!-- Réservations -->
    <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <p class="text-orange-100 text-sm">Réservations</p>
        <p class="text-3xl font-bold">{{ $reservations['total'] }}</p>
        <p class="text-xs text-orange-100 mt-1">{{ $reservations['actives'] }} actives</p>
    </div>
</div>

<!-- Graphiques et analyses détaillées -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Répartition CA -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Aperçu Financier</h2>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium">Chiffre d'Affaires Total</span>
                    <span class="text-sm font-bold text-green-600">{{ number_format($ca['total'], 2) }} €</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium">Ticket Moyen</span>
                    <span class="text-sm font-bold text-blue-600">{{ number_format($ca['moyenne'], 2) }} €</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min(($ca['moyenne'] / 50) * 100, 100) }}%"></div>
                </div>
            </div>

            <div class="pt-4 border-t">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-600">Commandes en attente</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $commandes['en_attente'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Commandes terminées</p>
                        <p class="text-2xl font-bold text-green-600">{{ $commandes['terminees'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses Réservations -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Réservations</h2>
        <div class="space-y-4">
            <div class="text-center py-6">
                <p class="text-6xl font-bold text-primary-600">{{ $reservations['total'] }}</p>
                <p class="text-gray-600 mt-2">Réservations sur la période</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center pt-4 border-t">
                <div>
                    <p class="text-sm text-gray-600">Actives</p>
                    <p class="text-2xl font-bold text-green-600">{{ $reservations['actives'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Taux de remplissage</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $reservations['total'] > 0 ? round(($reservations['actives'] / $reservations['total']) * 100) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top clients -->
<div class="card">
    <h2 class="text-xl font-semibold mb-4">🏆 Top 10 Clients</h2>
    
    @if($topClients->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucune donnée sur la période sélectionnée</p>
    @else
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Nombre de commandes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topClients as $index => $client)
                        <tr>
                            <td class="font-bold">
                                @if($index === 0)
                                    🥇
                                @elseif($index === 1)
                                    🥈
                                @elseif($index === 2)
                                    🥉
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="font-medium">{{ $client->nom }} {{ $client->prenom }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->numero }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $client->commandes_count }} commande{{ $client->commandes_count > 1 ? 's' : '' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
