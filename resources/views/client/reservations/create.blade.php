@extends('layouts.app')

@section('title', 'Nouvelle Réservation')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Nouvelle Réservation</h1>
    <p class="text-gray-600">Réservez une table pour votre prochaine visite</p>
</div>

<!-- Messages d'erreur -->
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $error)
            <p class="text-sm">• {{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Formulaire de réservation -->
    <div>
        <form method="POST" action="{{ route('client.reservations.store') }}" x-data="{
            selectedTable: '',
            dateDebut: '',
            duree: 120,
            nombrePersonne: 2,
            availability: null,
            checking: false,
            
            async checkAvailability() {
                if (!this.selectedTable || !this.dateDebut || !this.duree) {
                    return;
                }
                
                this.checking = true;
                this.availability = null;
                
                const dateFin = new Date(new Date(this.dateDebut).getTime() + this.duree * 60000).toISOString();
                
                try {
                    const response = await fetch('{{ route('client.reservations.check') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            idTable: this.selectedTable,
                            date_debut: this.dateDebut,
                            date_fin: dateFin
                        })
                    });
                    
                    const data = await response.json();
                    this.availability = data;
                    
                    if (data.available) {
                        toast('Table disponible !', 'success');
                    } else {
                        toast('Table non disponible sur cette période', 'error');
                    }
                } catch (error) {
                    toast('Erreur lors de la vérification', 'error');
                } finally {
                    this.checking = false;
                }
            }
        }">
            @csrf

            <div class="card">
                <h2 class="text-xl font-semibold mb-4">Informations de réservation</h2>

                <!-- Table -->
                <div class="mb-4">
                    <label for="idTable" class="label">Table *</label>
                    <select 
                        id="idTable" 
                        name="idTable" 
                        x-model="selectedTable"
                        @change="checkAvailability()"
                        class="select @error('idTable') border-red-500 @enderror" 
                        required
                    >
                        <option value="">Sélectionnez une table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->idTable }}">
                                Table {{ $table->numero }} (Capacité: {{ $table->capacite ?? '?' }} personnes)
                            </option>
                        @endforeach
                    </select>
                    @error('idTable')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre de personnes -->
                <div class="mb-4">
                    <label for="nombre_personne" class="label">Nombre de personnes *</label>
                    <input 
                        type="number" 
                        id="nombre_personne" 
                        name="nombre_personne" 
                        x-model="nombrePersonne"
                        min="1" 
                        max="20"
                        class="input @error('nombre_personne') border-red-500 @enderror" 
                        required
                    >
                    @error('nombre_personne')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date et heure de début -->
                <div class="mb-4">
                    <label for="date_debut" class="label">Date et heure de début *</label>
                    <input 
                        type="datetime-local" 
                        id="date_debut" 
                        name="date_debut" 
                        x-model="dateDebut"
                        @change="checkAvailability()"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="input @error('date_debut') border-red-500 @enderror" 
                        required
                    >
                    @error('date_debut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durée -->
                <div class="mb-4">
                    <label for="duree" class="label">Durée (en minutes) *</label>
                    <select 
                        id="duree" 
                        name="duree" 
                        x-model="duree"
                        @change="checkAvailability()"
                        class="select @error('duree') border-red-500 @enderror" 
                        required
                    >
                        <option value="30">30 minutes</option>
                        <option value="60">1 heure</option>
                        <option value="90">1h30</option>
                        <option value="120" selected>2 heures</option>
                        <option value="150">2h30 (maximum)</option>
                    </select>
                    @error('duree')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Indicateur de disponibilité -->
                <div x-show="availability !== null" class="mb-4">
                    <div 
                        :class="availability?.available ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
                        class="border px-4 py-3 rounded"
                    >
                        <p x-text="availability?.message"></p>
                    </div>
                </div>

                <div x-show="checking" class="mb-4 text-center text-gray-600">
                    <svg class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Vérification...
                </div>

                <!-- Boutons -->
                <div class="flex space-x-3">
                    <button 
                        type="submit" 
                        class="btn btn-primary flex-1"
                        :disabled="availability && !availability.available"
                    >
                        Confirmer la réservation
                    </button>
                    <a href="{{ route('client.reservations.index') }}" class="btn btn-outline">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Informations -->
    <div class="space-y-4">
        <!-- Informations importantes -->
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">ℹ️ Informations importantes</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>• La durée maximale d'une réservation est de 2h30</li>
                <li>• Votre réservation sera confirmée immédiatement</li>
                <li>• Vous pouvez annuler votre réservation à tout moment avant l'heure prévue</li>
                <li>• Veuillez arriver à l'heure pour profiter pleinement de votre réservation</li>
            </ul>
        </div>

        <!-- Tables disponibles -->
        <div class="card">
            <h3 class="font-semibold mb-3">Tables disponibles</h3>
            <div class="space-y-2">
                @foreach($tables as $table)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">Table {{ $table->numero }}</p>
                            <p class="text-sm text-gray-600">{{ $table->capacite }} personnes</p>
                        </div>
                        <span class="badge 
                            @if($table->statut == 'Libre') badge-success
                            @else badge-warning
                            @endif
                        ">
                            {{ $table->statut }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
