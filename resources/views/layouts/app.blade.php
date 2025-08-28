<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <img src="/logo-promocion138.svg" alt="Promoción 138" class="w-8 h-8 mr-3" />
                        <span class="font-bold text-yellow-400">Promoción 138 - Escuela Naval Militar</span>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="mb-2 text-sm text-gray-300">
                            "Inmare Pro Patria Luctati Honore" - En el Mar y Por la Patria Lucharemos con Honor
                        </p>
                        <p class="text-xs text-gray-400">
                            © 2025 Promoción 138. Sistema desarrollado con Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                        </p>
                    </div>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
