<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Promoción 138 - Sistema de Pagos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen text-white bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
            <div class="relative flex flex-col items-center justify-center min-h-screen">
                <div class="relative w-full max-w-6xl px-6">
                    <header class="flex items-center justify-between py-10">
                        <div class="flex items-center space-x-4">
                            <x-application-logo class="w-16 h-16 text-yellow-400" />
                            <div>
                                <h1 class="text-2xl font-bold text-yellow-400">Promoción 138</h1>
                                <p class="text-blue-200">Escuela Naval Militar</p>
                            </div>
                        </div>
                        @if (Route::has('login'))
                            <livewire:welcome.navigation />
                        @endif
                    </header>

                    <main class="mt-8">
                        <!-- Hero Section -->
                        <div class="mb-16 text-center">
                            <h2 class="mb-6 text-5xl font-bold text-white">
                                Bienvenidos a <span class="text-yellow-400">Promoción 138</span>
                            </h2>
                            <p class="max-w-3xl mx-auto mb-8 text-xl text-blue-200">
                                Sistema de gestión y control de pagos mensuales para egresados de la Escuela Naval Militar.
                                Manteniendo unidos a más de 100 compañeros de promoción.
                            </p>
                            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                                <button class="px-8 py-4 font-bold text-blue-900 transition duration-300 bg-yellow-500 rounded-lg hover:bg-yellow-400">
                                    Acceder al Sistema
                                </button>
                                <button class="px-8 py-4 font-bold text-yellow-400 transition duration-300 border-2 border-yellow-400 rounded-lg hover:bg-yellow-400 hover:text-blue-900">
                                    Conocer Más
                                </button>
                            </div>
                        </div>

                        <!-- Features Grid -->
                        <div class="grid gap-8 lg:grid-cols-3">
                            <!-- Payments Card -->
                            <div class="p-8 border bg-white/10 backdrop-blur-sm rounded-xl border-white/20">
                                <div class="flex items-center justify-center mb-6 rounded-full size-16 shrink-0 bg-yellow-500/20">
                                    <svg class="text-yellow-400 size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mb-4 text-2xl font-bold text-white">Gestión de Pagos</h3>
                                <p class="mb-6 text-blue-200">
                                    Control completo de las cuotas mensuales de cada miembro de la promoción.
                                    Registro de pagos, estados de cuenta y recordatorios automáticos.
                                </p>
                                <div class="font-semibold text-yellow-400">100 miembros activos →</div>
                            </div>

                            <!-- Members Card -->
                            <div class="p-8 border bg-white/10 backdrop-blur-sm rounded-xl border-white/20">
                                <div class="flex items-center justify-center mb-6 rounded-full size-16 shrink-0 bg-yellow-500/20">
                                    <svg class="text-yellow-400 size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mb-4 text-2xl font-bold text-white">Directorio Naval</h3>
                                <p class="mb-6 text-blue-200">
                                    Directorio completo de todos los egresados de la Promoción 138.
                                    Información de contacto, estados de servicio y comunicación grupal.
                                </p>
                                <div class="font-semibold text-yellow-400">Ver directorio →</div>
                            </div>

                            <!-- Reports Card -->
                            <div class="p-8 border bg-white/10 backdrop-blur-sm rounded-xl border-white/20">
                                <div class="flex items-center justify-center mb-6 rounded-full size-16 shrink-0 bg-yellow-500/20">
                                    <svg class="text-yellow-400 size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h3 class="mb-4 text-2xl font-bold text-white">Reportes y Estadísticas</h3>
                                <p class="mb-6 text-blue-200">
                                    Reportes detallados sobre el estado financiero de la promoción.
                                    Gráficos, estadísticas de pagos y análisis de participación.
                                </p>
                                <div class="font-semibold text-yellow-400">Ver reportes →</div>
                            </div>
                        </div>

                        <!-- Stats Section -->
                        <div class="grid grid-cols-2 gap-8 mt-16 lg:grid-cols-4">
                            <div class="text-center">
                                <div class="mb-2 text-4xl font-bold text-yellow-400">138</div>
                                <div class="text-blue-200">Promoción Naval</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-2 text-4xl font-bold text-yellow-400">100+</div>
                                <div class="text-blue-200">Miembros Activos</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-2 text-4xl font-bold text-yellow-400">12</div>
                                <div class="text-blue-200">Meses de Gestión</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-2 text-4xl font-bold text-yellow-400">95%</div>
                                <div class="text-blue-200">Pago Puntual</div>
                            </div>
                        </div>
                    </main>

                    <footer class="py-16 text-center">
                        <div class="pt-8 border-t border-white/20">
                            <div class="flex items-center justify-center mb-4">
                                <img src="/logo-promocion138.svg" alt="Promoción 138" class="w-8 h-8 mr-3" />
                                <span class="font-bold text-yellow-400">Promoción 138 - Escuela Naval Militar</span>
                            </div>
                            <p class="mb-2 text-sm text-blue-300">
                                "Inmare Pro Patria Luctati Honore" - En el Mar y Por la Patria Lucharemos con Honor
                            </p>
                            <p class="text-xs text-blue-400">
                                © 2025 Promoción 138. Sistema desarrollado con Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                            </p>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
