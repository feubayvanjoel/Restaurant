@extends('layouts.app')

@section('title', 'Historique des Encaissements')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Historique des Encaissements</h1>
            <p class="text-gray-600">Encaissements du jour</p>
        </div>
        <a href="{{ route('caissier.dashboard') }}" class="btn btn-outline">
            ← Retour
        </a>
    </div>
</div>

<!-- Statistiques du jour -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Total Encaissé</p>
        <p class="text-3xl font-bold">{{ number_format($stats['total_jour'], 2) }} €</p>
    </div>

    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Nombre de Tickets</p>
        <p class="text-3xl font-bold">{{ $stats['nombre_tickets'] }}</p>
    </div>

    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Ticket Moyen</p>
        <p class="text-3xl font-bold">{{ number_format($stats['ticket_moyen'], 2) }} €</p>
    </div>
</div>

<!-- Liste des encaissements -->
@if($encaissements->isEmpty())
    <div class="card text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun encaissement</h3>
        <p class="text-gray-600">Aucun encaissement n'a été effectué aujourd'hui</p>
    </div>
@else
    <div class="card overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Commande</th>
                    <th>Ticket</th>
                    <th>Table</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($encaissements as $commande)
                    <tr>
                        <td class="font-medium">
                            {{ \Carbon\Carbon::parse($commande->horaire)->format('H:i') }}
                        </td>
                        <td>
                            <span class="badge badge-info">#{{ $commande->idCommande }}</span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">#{{ $commande->ticket->idTicket ?? 'N/A' }}</span>
                        </td>
                        <td class="font-semibold">
                            Table {{ $commande->table->numero ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}
                        </td>
                        <td class="font-bold text-green-600 text-lg">
                            {{ number_format($commande->ticket->prix ?? 0, 2) }} €
                        </td>
                        <td>
                            <a href="{{ route('client.commandes.ticket', $commande) }}" 
                               class="btn btn-sm btn-outline"
                               target="_blank">
                                📄 Ticket
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-green-50 font-bold">
                    <td colspan="5" class="text-right">TOTAL :</td>
                    <td class="text-green-600 text-xl">{{ number_format($stats['total_jour'], 2) }} €</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Résumé par période -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Répartition par heure -->
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Activité par heure</h3>
            <div class="space-y-2">
                @php
                    $parHeure = $encaissements->groupBy(function($commande) {
                        return \Carbon\Carbon::parse($commande->horaire)->format('H:00');
                    });
                @endphp
                
                @foreach($parHeure as $heure => $commandes)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $heure }}</p>
                            <p class="text-xs text-gray-600">{{ $commandes->count() }} encaissement(s)</p>
                        </div>
                        <p class="font-bold text-primary-600">
                            {{ number_format($commandes->sum(function($c) { return $c->ticket->prix ?? 0; }), 2) }} €
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top tables -->
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Top 5 Tables</h3>
            <div class="space-y-2">
                @php
                    $parTable = $encaissements->groupBy('idTable')
                        ->map(function($commandes) {
                            return [
                                'numero' => $commandes->first()->table->numero ?? 'N/A',
                                'count' => $commandes->count(),
                                'total' => $commandes->sum(function($c) { return $c->ticket->prix ?? 0; })
                            ];
                        })
                        ->sortByDesc('total')
                        ->take(5);
                @endphp
                
                @foreach($parTable as $table)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">Table {{ $table['numero'] }}</p>
                            <p class="text-xs text-gray-600">{{ $table['count'] }} commande(s)</p>
                        </div>
                        <p class="font-bold text-primary-600">{{ number_format($table['total'], 2) }} €</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection
