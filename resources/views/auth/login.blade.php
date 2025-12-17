@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="card">
    <!-- Logo/Titre -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Joel Restau Logo" class="h-16 w-auto">
        </div>
        <h1 class="text-4xl font-bold text-primary-600 mb-2">Joel Restau</h1>
        <p class="text-gray-600">Connectez-vous à votre compte</p>
    </div>

    <!-- Messages de succès -->
    @if(session('success'))
        <div class="bg-secondary-100 border border-secondary-400 text-secondary-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Formulaire de connexion -->
    <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true">
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
                placeholder="Entrez votre login"
            >
            @error('login')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mot de passe -->
        <div class="mb-6">
            <label for="password" class="label">Mot de passe</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="input @error('password') border-red-500 @enderror" 
                required
                placeholder="Entrez votre mot de passe"
            >
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Se souvenir de moi -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-800">
                Mot de passe oublié ?
            </a>
        </div>

        <!-- Bouton de connexion -->
        <button 
            type="submit" 
            class="w-full btn btn-primary"
            :disabled="loading"
        >
            <span x-show="!loading">Se connecter</span>
            <span x-show="loading" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Connexion...
            </span>
        </button>
    </form>

    <!-- Lien d'inscription -->
    <div class="mt-6 text-center">
        <p class="text-gray-600">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-800 font-medium">
                S'inscrire
            </a>
        </p>
    </div>
</div>
@endsection
