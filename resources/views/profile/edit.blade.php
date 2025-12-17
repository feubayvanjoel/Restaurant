@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Mon Profil</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Informations Personnelles</h2>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="nom" class="label">Nom</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $proprietaire->nom) }}" class="input" required>
                        @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="prenom" class="label">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $proprietaire->prenom) }}" class="input" required>
                        @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $proprietaire->email) }}" class="input" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="numero" class="label">Téléphone</label>
                        <div class="relative">
                            <input type="tel" id="numero" name="numero" value="{{ old('numero', $proprietaire->numero) }}" class="phone-input input w-full">
                        </div>
                        @error('numero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="adresse" class="label">Adresse</label>
                        <textarea id="adresse" name="adresse" class="input" rows="3">{{ old('adresse', $proprietaire->adresse) }}</textarea>
                        @error('adresse') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary w-full">
                            Mettre à jour les informations
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sécurité (Mot de passe) -->
        <div class="card h-fit">
            <h2 class="text-xl font-semibold mb-4">Sécurité</h2>
            
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="label">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="input" required>
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="label">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" class="input" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="label">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-outline w-full">
                            Changer le mot de passe
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
