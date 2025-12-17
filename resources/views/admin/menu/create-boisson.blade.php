@extends('layouts.app')

@section('title', 'Ajouter une Boisson')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Ajouter une Boisson</h1>
    <p class="text-gray-600">Créer une nouvelle boisson</p>
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
        <form method="POST" action="{{ route('admin.menu.boissons.store') }}">
            @csrf

            <div class="card">
                <h2 class="text-xl font-semibold mb-4">Informations de la boisson</h2>
                
                <div class="mb-4">
                    <label for="nom" class="label">Nom de la boisson *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" 
                           class="input" required placeholder="Ex: Coca-Cola 33cl">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="prix" class="label">Prix (€) *</label>
                        <input type="number" id="prix" name="prix" value="{{ old('prix') }}" 
                               class="input" required step="0.01" min="0" placeholder="2.50">
                    </div>

                    <div>
                        <label for="quantite" class="label">Quantité initiale *</label>
                        <input type="number" id="quantite" name="quantite" value="{{ old('quantite', 0) }}" 
                               class="input" required min="0" placeholder="100">
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="btn btn-primary">Créer la boisson</button>
                    <a href="{{ route('admin.menu.index', ['tab' => 'boissons']) }}" class="btn btn-outline">Annuler</a>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">💡 Conseils</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>• Incluez la taille dans le nom (33cl, 50cl, 1L...)</li>
                <li>• Le prix doit être en euros</li>
                <li>• La quantité peut être ajustée dans la gestion du stock</li>
                <li>• Une boisson avec quantité = 0 sera en rupture</li>
            </ul>
        </div>
    </div>
</div>
@endsection
