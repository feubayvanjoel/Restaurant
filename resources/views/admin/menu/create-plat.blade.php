@extends('layouts.app')

@section('title', 'Ajouter un Plat')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Ajouter un Plat</h1>
    <p class="text-gray-600">Créer un nouveau plat</p>
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
        <form method="POST" action="{{ route('admin.menu.plats.store') }}">
            @csrf

            <div class="card">
                <h2 class="text-xl font-semibold mb-4">Informations du plat</h2>
                
                <div class="mb-4">
                    <label for="nom" class="label">Nom du plat *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" 
                           class="input" required placeholder="Ex: Pizza Margherita">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="prix" class="label">Prix (€) *</label>
                        <input type="number" id="prix" name="prix" value="{{ old('prix') }}" 
                               class="input" required step="0.01" min="0" placeholder="12.50">
                    </div>

                    <div>
                        <label for="quantite" class="label">Quantité initiale *</label>
                        <input type="number" id="quantite" name="quantite" value="{{ old('quantite', 0) }}" 
                               class="input" required min="0" placeholder="50">
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="btn btn-primary">Créer le plat</button>
                    <a href="{{ route('admin.menu.index', ['tab' => 'plats']) }}" class="btn btn-outline">Annuler</a>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">💡 Conseils</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>• Choisissez un nom clair et descriptif</li>
                <li>• Le prix doit être en euros (utilisez . pour les décimales)</li>
                <li>• La quantité peut être modifiée ultérieurement dans la gestion du stock</li>
                <li>• Un plat avec quantité = 0 sera marqué en rupture</li>
            </ul>
        </div>
    </div>
</div>
@endsection
