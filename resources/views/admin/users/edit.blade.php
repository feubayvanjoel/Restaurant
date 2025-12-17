@extends('layouts.app')

@section('title', 'Modifier le Profil')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="group p-2 rounded-lg border border-gray-200 bg-white text-gray-400 hover:text-gray-900 hover:border-gray-300 transition-all shadow-sm">
                    <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        {{ $user->nom }} {{ $user->prenom }}
                    </h1>
                    <p class="text-sm text-gray-500">
                        {{ $type === 'personnel' ? 'Membre de l\'équipe' : 'Client' }} • Mise à jour des informations
                    </p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="text-sm text-red-700">
                    <p class="font-medium">Formulaire incomplet</p>
                    <ul class="mt-1 list-disc list-inside opacity-90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', ['type' => $type, 'id' => $type === 'client' ? $user->idClient : $user->idPersonnel]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Column: Personal Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-base font-semibold text-gray-900">Information Personnelle</h2>
                            <p class="text-sm text-gray-500 mt-1">Identité et coordonnées de contact.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-all">
                            </div>
                            <div class="space-y-1 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                        class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                            <div class="space-y-1 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="tel" name="numero" value="{{ old('numero', $user->numero) }}" required 
                                        class="phone-input block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                            <div class="space-y-1 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Adresse</label>
                                <textarea name="adresse" rows="3" required 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('adresse', $user->adresse) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Column: Account -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                             <h2 class="text-base font-semibold text-gray-900">Accès & Sécurité</h2>
                             <p class="text-sm text-gray-500 mt-1">Mise à jour des accès.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            @if($type === 'personnel')
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select name="poste" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                        <option value="ADMIN" {{ old('poste', $user->poste) === 'ADMIN' ? 'selected' : '' }}>Administrateur</option>
                                        <option value="CAISSIER" {{ old('poste', $user->poste) === 'CAISSIER' ? 'selected' : '' }}>Caissier</option>
                                        <option value="CUISINIER" {{ old('poste', $user->poste) === 'CUISINIER' ? 'selected' : '' }}>Cuisinier</option>
                                        <option value="SERVEUR" {{ old('poste', $user->poste) === 'SERVEUR' ? 'selected' : '' }}>Serveur</option>
                                    </select>
                                </div>
                            @endif

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Identifiant</label>
                                <input type="text" value="{{ $user->compte->login }}" disabled 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm py-2.5 bg-gray-100 text-gray-500 cursor-not-allowed">
                                <p class="text-xs text-gray-400 pt-1">L'identifiant ne peut pas être modifié.</p>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                <input type="password" name="password" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5" 
                                    placeholder="Laisser vide pour conserver">
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Enregistrer
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="mt-3 w-full flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                                    Annuler
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
