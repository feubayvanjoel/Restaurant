@extends('layouts.app')

@section('title', 'Éditer Boisson')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Éditer la Boisson</h1>
    <p class="text-gray-600">Modifier les informations de la boisson</p>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $error)
            <p class="text-sm">• {{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('admin.menu.boissons.update', $boisson) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <h2 class="text-xl font-semibold mb-4">Informations de la boisson</h2>
                
                <div class="mb-4">
                    <label for="nom" class="label">Nom de la boisson *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $boisson->nom) }}" 
                           class="input" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="prix" class="label">Prix (€) *</label>
                        <input type="number" id="prix" name="prix" value="{{ old('prix', $boisson->prix) }}" 
                               class="input" required step="0.01" min="0">
                    </div>

                    <div>
                        <label for="quantite" class="label">Quantité *</label>
                        <input type="number" id="quantite" name="quantite" value="{{ old('quantite', $boisson->quantite) }}" 
                               class="input" required min="0">
                        <p class="text-xs text-gray-600 mt-1">Modifiable aussi dans la gestion du stock</p>
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('admin.menu.index', ['tab' => 'boissons']) }}" class="btn btn-outline">Annuler</a>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="card sticky top-6">
            <h3 class="font-semibold mb-4">Informations</h3>
            <div class="space-y-2 text-sm">
                <p class="text-gray-600"><strong>ID:</strong> {{ $boisson->idBoissons }}</p>
                <p class="text-gray-600"><strong>Statut:</strong> 
                    @if($boisson->quantite === 0)
                        <span class="badge badge-danger">Rupture</span>
                    @elseif($boisson->quantite < 10)
                        <span class="badge badge-warning">Stock faible</span>
                    @else
                        <span class="badge badge-success">Disponible</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
