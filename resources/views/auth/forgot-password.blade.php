@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="card">
    <!-- Logo/Titre -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-primary-600 mb-2">🔒 Mot de passe oublié</h1>
        <p class="text-gray-600">Entrez votre login et email pour réinitialiser votre mot de passe</p>
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
    <form method="POST" action="{{ route('password.verify') }}">
        @csrf

        <!-- Login -->
        <div class="mb-4">
            <label for="login" class="label">Login</label>
            <input 
                type="text" 
                id="login" 
                name="login" 
                value="{{ old('login') }}"
                class="input @error('login') border-red-500 @enderror" 
                required 
                autofocus
                placeholder="Votre login"
            >
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="label">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                class="input @error('email') border-red-500 @enderror" 
                required
                placeholder="Votre email"
            >
        </div>

        <!-- Bouton -->
        <button type="submit" class="w-full btn btn-primary">
            Vérifier
        </button>
    </form>

    <!-- Retour à la connexion -->
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-800">
            ← Retour à la connexion
        </a>
    </div>
</div>
@endsection
