@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')

@section('content')
<div class="card">
    <!-- Logo/Titre -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-primary-600 mb-2">🔑 Nouveau mot de passe</h1>
        <p class="text-gray-600">Entrez votre nouveau mot de passe</p>
        <p class="text-sm text-gray-500 mt-2">Login: <strong>{{ $login }}</strong></p>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            @foreach($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Nouveau mot de passe -->
        <div class="mb-4">
            <label for="password" class="label">Nouveau mot de passe</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="input @error('password') border-red-500 @enderror" 
                required
                placeholder="Min. 6 caractères"
            >
        </div>

        <!-- Confirmation -->
        <div class="mb-6">
            <label for="password_confirmation" class="label">Confirmer le mot de passe</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="input" 
                required
                placeholder="Confirmez votre nouveau mot de passe"
            >
        </div>

        <!-- Bouton -->
        <button type="submit" class="w-full btn btn-primary">
            Réinitialiser le mot de passe
        </button>
    </form>
</div>
@endsection
