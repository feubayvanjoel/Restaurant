@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans text-gray-900" 
     x-data="{ 
         tab: '{{ $type === 'personnel' ? 'personnel' : 'clients' }}',
         search: '',
         deleteModalOpen: false,
         deleteUrl: '',
         confirmDelete(url) {
             this.deleteUrl = url;
             this.deleteModalOpen = true;
         }
     }">

    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Title -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">Utilisateurs</span>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                     <a :href="'{{ route('admin.users.create') }}' + '?type=' + tab" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        <span x-text="tab === 'clients' ? 'Ajouter nouveau client' : 'Ajouter nouveau personnel'"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <!-- Tabs -->
            <div class="flex p-1 space-x-1 bg-gray-100/80 rounded-lg">
                <button @click="tab = 'clients'" 
                    class="w-32 py-2 text-sm font-medium rounded-md focus:outline-none transition-all duration-200"
                    :class="tab === 'clients' ? 'bg-white text-gray-900 shadow ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-900'">
                    Clients
                </button>
                <button @click="tab = 'personnel'" 
                    class="w-32 py-2 text-sm font-medium rounded-md focus:outline-none transition-all duration-200"
                    :class="tab === 'personnel' ? 'bg-white text-indigo-600 shadow ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-900'">
                    Personnel
                </button>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Filtrer..." class="block w-full pl-9 pr-3 py-2 sm:text-sm bg-white border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition-colors shadow-sm">
            </div>
        </div>

        <!-- Clients List -->
        <div x-show="tab === 'clients'" class="space-y-4"  style="display: none;">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <ul role="list" class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <li class="relative hover:bg-gray-50 transition-colors duration-150 group" 
                        x-show="search === '' || '{{ strtolower($client->nom . ' ' . $client->prenom . ' ' . $client->email) }}'.includes(search.toLowerCase())">
                        <div class="px-6 py-5 flex items-center justify-between">
                            <div class="flex items-center min-w-0 gap-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors font-medium text-sm">
                                        {{ substr($client->prenom, 0, 1) }}{{ substr($client->nom, 0, 1) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate flex items-center gap-2">
                                        {{ $client->prenom }} {{ $client->nom }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">{{ $client->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-8">
                                <div class="hidden sm:flex flex-col items-end">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        Client
                                    </span>
                                    <span class="text-xs text-gray-400 mt-1">{{ $client->numero }}</span>
                                </div>
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', ['type' => 'client', 'id' => $client->idClient]) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <button @click="confirmDelete('{{ route('admin.users.destroy', ['type' => 'client', 'id' => $client->idClient]) }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Aucun client</p>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau client.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Personnel List (Grid) -->
        <div x-show="tab === 'personnel'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" style="display: none;">
            @forelse($personnel as $employe)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 relative group overflow-hidden" 
                 x-show="search === '' || '{{ strtolower($employe->nom . ' ' . $employe->prenom . ' ' . $employe->email . ' ' . $employe->poste) }}'.includes(search.toLowerCase())">
                
                 <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="h-12 w-12 rounded-lg bg-gray-900 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            {{ substr($employe->prenom, 0, 1) }}
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide
                            @if($employe->poste === 'ADMIN') bg-rose-50 text-rose-700 border border-rose-100
                            @elseif($employe->poste === 'CUISINIER') bg-amber-50 text-amber-700 border border-amber-100
                            @elseif($employe->poste === 'SERVEUR') bg-teal-50 text-teal-700 border border-teal-100
                            @elseif($employe->poste === 'CAISSIER') bg-violet-50 text-violet-700 border border-violet-100
                            @else bg-gray-50 text-gray-700 border border-gray-100
                            @endif">
                            {{ $employe->poste }}
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $employe->prenom }} {{ $employe->nom }}</h3>
                    <p class="text-sm text-gray-500 mt-1 truncate">{{ $employe->email }}</p>
                    
                    <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-50">
                        <span class="text-xs font-mono text-gray-400">{{ $employe->numero }}</span>
                        
                        <div class="flex gap-2">
                             <a href="{{ route('admin.users.edit', ['type' => 'personnel', 'id' => $employe->idPersonnel]) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded bg-transparent hover:bg-gray-100 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <button @click="confirmDelete('{{ route('admin.users.destroy', ['type' => 'personnel', 'id' => $employe->idPersonnel]) }}')" class="p-1.5 text-gray-400 hover:text-red-600 rounded bg-transparent hover:bg-red-50 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                 </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl">
                 <p class="text-sm font-medium text-gray-900">Aucun personnel</p>
                 <p class="mt-1 text-sm text-gray-500">Votre équipe est vide.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Minimal Modal -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="deleteModalOpen = false">
                <div class="absolute inset-0 bg-gray-900 opacity-20"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">
                    Confirmer la suppression
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Cette action est définitive.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <form method="POST" :action="deleteUrl">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
