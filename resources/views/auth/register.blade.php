@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div class="card max-w-2xl">
    <!-- Logo/Titre -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Joel Restau Logo" class="h-16 w-auto">
        </div>
        <h1 class="text-4xl font-bold text-primary-600 mb-2">Joel Restau</h1>
        <p class="text-gray-600">Créez votre compte client</p>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            @foreach($errors->all() as $error)
                <p class="text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('register') }}" x-data="{ loading: false, countryCode: '+32' }" @submit="loading = true">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nom -->
            <div>
                <label for="nom" class="label">Nom *</label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    value="{{ old('nom') }}"
                    class="input @error('nom') border-red-500 @enderror" 
                    required
                    placeholder="Dupont"
                >
                @error('nom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="label">Prénom *</label>
                <input 
                    type="text" 
                    id="prenom" 
                    name="prenom" 
                    value="{{ old('prenom') }}"
                    class="input @error('prenom') border-red-500 @enderror" 
                    required
                    placeholder="Jean"
                >
                @error('prenom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="label">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="input @error('email') border-red-500 @enderror" 
                    required
                    placeholder="jean.dupont@email.com"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Numéro de téléphone -->
            <!-- Numéro de téléphone -->
            <div>
                <label for="numero" class="label">Numéro de téléphone *</label>
                <div class="relative">
                    <input 
                        type="tel" 
                        id="numero" 
                        name="numero" 
                        value="{{ old('numero') }}"
                        class="phone-input input w-full @error('numero') border-red-500 @enderror" 
                        required
                    >
                </div>
                @error('numero')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Adresse -->
        <div class="mt-4">
            <label for="adresse" class="label">Adresse complète *</label>
            <textarea 
                id="adresse" 
                name="adresse" 
                rows="2"
                class="textarea @error('adresse') border-red-500 @enderror" 
                required
                placeholder="Rue, numéro, code postal, ville"
            >{{ old('adresse') }}</textarea>
            @error('adresse')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ligne de séparation -->
        <hr class="my-6">

        <!-- Login -->
        <div class="mb-4">
            <label for="login" class="label">Login *</label>
            <input 
                type="text" 
                id="login" 
                name="login" 
                value="{{ old('login') }}"
                class="input @error('login') border-red-500 @enderror" 
                required
                placeholder="Choisissez un login unique"
            >
            @error('login')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Mot de passe -->
            <div>
                <label for="password" class="label">Mot de passe *</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="input @error('password') border-red-500 @enderror" 
                    required
                    placeholder="Min. 6 caractères"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation mot de passe -->
            <div>
                <label for="password_confirmation" class="label">Confirmer le mot de passe *</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="input" 
                    required
                    placeholder="Confirmez votre mot de passe"
                >
            </div>
        </div>

        <!-- Bouton d'inscription -->
        <button 
            type="submit" 
            class="w-full btn btn-primary mt-6"
            :disabled="loading"
        >
            <span x-show="!loading">S'inscrire</span>
            <span x-show="loading" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Inscription...
            </span>
        </button>
    </form>

    <!-- Lien de connexion -->
    <div class="mt-6 text-center">
        <p class="text-gray-600">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 font-medium">
                Se connecter
            </a>
        </p>
    </div>
</div>
@endsection
