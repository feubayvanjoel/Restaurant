<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Restaurant</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <style>
        .iti { width: 100%; }
        .iti__dropdown-content { z-index: 100 !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="flex">
        <!-- Sidebar (optionnel selon la page) -->
        @hasSection('sidebar')
            <aside class="w-64 bg-white shadow-md min-h-screen">
                @yield('sidebar')
            </aside>
        @endif

        <!-- Contenu principal -->
        <main class="flex-1 p-6">
            <!-- Messages flash -->
            @if(session('success'))
                <div class="bg-secondary-100 border border-secondary-400 text-secondary-700 px-4 py-3 rounded mb-4" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- Scripts supplémentaires -->
    @stack('scripts')

    <!-- Phone Input Script -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
    <script>
        // Vérifier que Alpine.js est chargé
        document.addEventListener("DOMContentLoaded", function() {
            console.log('Alpine.js loaded:', window.Alpine !== undefined);
            console.log('menuManager available:', window.menuManager !== undefined);

            const inputs = document.querySelectorAll(".phone-input");
            inputs.forEach(input => {
                const iti = window.intlTelInput(input, {
                    initialCountry: "be",
                    strictMode: true,
                    separateDialCode: true,
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js", // for formatting/validation
                });

                // Update input value with full number on form submit
                input.closest('form').addEventListener('submit', function() {
                    const fullNumber = iti.getNumber();
                    if (fullNumber) {
                        input.value = fullNumber;
                    }
                });
            });
        });
    </script>
</body>
</html>
