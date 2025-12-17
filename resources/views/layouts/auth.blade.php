<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Restaurant</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <style>
        .iti { width: 100%; }
        .iti__dropdown-content { z-index: 100 !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-50 to-secondary-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
