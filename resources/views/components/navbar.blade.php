<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et navigation -->
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route(strtolower(auth()->user()->role) . '.dashboard') }}" class="flex items-center gap-2 text-2xl font-bold text-primary-600">
                        <img src="{{ asset('logo.png') }}" alt="Joel Restau Logo" class="h-12 w-auto object-contain">
                        <span>Joel Restau</span>
                    </a>
                </div>

                <!-- Navigation principale selon le rôle -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    @if(auth()->user()->role === 'ADMIN')
                        <a href="{{ route('admin.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Tableau de bord
                        </a>
                        <!-- Autres liens ADMIN à ajouter -->
                    @elseif(auth()->user()->role === 'CLIENT')
                        <a href="{{ route('client.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Mes commandes
                        </a>
                        <!-- Autres liens CLIENT à ajouter -->
                    @elseif(auth()->user()->role === 'CAISSIER')
                        <a href="{{ route('caissier.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Paiements
                        </a>
                    @elseif(auth()->user()->role === 'SERVEUR')
                        <a href="{{ route('serveur.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Service
                        </a>
                    @elseif(auth()->user()->role === 'CUISINIER')
                        <a href="{{ route('cuisinier.dashboard') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Commandes
                        </a>
                    @endif
                </div>
            </div>

            <!-- Menu utilisateur -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <!-- Dropdown -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <span class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                {{ auth()->user()->proprietaire->nom ?? 'Utilisateur' }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                        <div class="px-4 py-2 text-xs text-gray-400">
                            {{ auth()->user()->login }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
