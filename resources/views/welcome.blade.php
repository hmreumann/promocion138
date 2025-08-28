<x-app-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239C92AC\" fill-opacity=\"0.05\"%3E%3Cpath d=\"m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        <div class="relative px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8 md:py-24">
            <div class="text-center">
                <div class="flex flex-col items-center justify-center mb-8 sm:flex-row">
                    <x-application-logo class="w-16 h-16 mb-4 text-yellow-400 md:w-20 md:h-20 sm:mb-0" />
                    <div class="sm:ml-6">
                        <h1 class="text-3xl font-bold text-yellow-400 md:text-4xl">Promoción 138</h1>
                        <p class="text-sm text-blue-200 md:text-base">Escuela Naval Militar</p>
                    </div>
                </div>

                <h2 class="mb-6 text-4xl font-bold text-white md:text-5xl lg:text-6xl">
                    Bienvenidos a <span class="text-yellow-400">Promoción 138</span>
                </h2>
                <p class="max-w-3xl px-4 mx-auto mb-8 text-lg text-blue-200 md:text-xl">
                    Sistema de gestión y control de pagos mensuales para egresados de la Escuela Naval Militar.
                    Manteniendo unidos a más de 100 compañeros de promoción.
                </p>
                <div class="flex flex-col justify-center gap-4 px-4 sm:flex-row">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 font-bold text-center text-blue-900 transition duration-300 bg-yellow-500 rounded-lg md:px-8 md:py-4 hover:bg-yellow-400">
                            Ir al Dashboard
                        </a>
                        <a href="{{ route('invoices.index') }}" class="px-6 py-3 font-bold text-center text-yellow-400 transition duration-300 border-2 border-yellow-400 rounded-lg md:px-8 md:py-4 hover:bg-yellow-400 hover:text-blue-900">
                            Ver Mis Facturas
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 font-bold text-center text-blue-900 transition duration-300 bg-yellow-500 rounded-lg md:px-8 md:py-4 hover:bg-yellow-400">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white md:py-24">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Payments Card -->
                <div class="p-6 transition-shadow border border-gray-200 md:p-8 bg-gray-50 rounded-xl hover:shadow-lg">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-6 rounded-full md:w-16 md:h-16 bg-blue-500/20">
                        <svg class="w-6 h-6 text-blue-600 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="mb-4 text-xl font-bold text-center text-gray-900 md:text-2xl">Gestión de Pagos</h3>
                    <p class="mb-6 text-center text-gray-600">
                        Control completo de las cuotas mensuales de cada miembro de la promoción.
                        Registro de pagos, estados de cuenta y recordatorios automáticos.
                    </p>
                    <div class="font-semibold text-center text-blue-600">100 miembros activos →</div>
                </div>

                <!-- Members Card -->
                <div class="p-6 transition-shadow border border-gray-200 md:p-8 bg-gray-50 rounded-xl hover:shadow-lg">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-6 rounded-full md:w-16 md:h-16 bg-blue-500/20">
                        <svg class="w-6 h-6 text-blue-600 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="mb-4 text-xl font-bold text-center text-gray-900 md:text-2xl">Directorio Naval</h3>
                    <p class="mb-6 text-center text-gray-600">
                        Directorio completo de todos los egresados de la Promoción 138.
                        Información de contacto, estados de servicio y comunicación grupal.
                    </p>
                    <div class="font-semibold text-center text-blue-600">Ver directorio →</div>
                </div>

                <!-- Reports Card -->
                <div class="p-6 transition-shadow border border-gray-200 md:p-8 bg-gray-50 rounded-xl hover:shadow-lg">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-6 rounded-full md:w-16 md:h-16 bg-blue-500/20">
                        <svg class="w-6 h-6 text-blue-600 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="mb-4 text-xl font-bold text-center text-gray-900 md:text-2xl">Reportes y Estadísticas</h3>
                    <p class="mb-6 text-center text-gray-600">
                        Reportes detallados sobre el estado financiero de la promoción.
                        Gráficos, estadísticas de pagos y análisis de participación.
                    </p>
                    <div class="font-semibold text-center text-blue-600">Ver reportes →</div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-2 gap-6 mt-16 md:gap-8 lg:grid-cols-4">
                <div class="text-center">
                    <div class="mb-2 text-3xl font-bold text-blue-600 md:text-4xl">138</div>
                    <div class="text-sm text-gray-600 md:text-base">Promoción Naval</div>
                </div>
                <div class="text-center">
                    <div class="mb-2 text-3xl font-bold text-blue-600 md:text-4xl">100+</div>
                    <div class="text-sm text-gray-600 md:text-base">Miembros Activos</div>
                </div>
                <div class="text-center">
                    <div class="mb-2 text-3xl font-bold text-blue-600 md:text-4xl">12</div>
                    <div class="text-sm text-gray-600 md:text-base">Meses de Gestión</div>
                </div>
                <div class="text-center">
                    <div class="mb-2 text-3xl font-bold text-blue-600 md:text-4xl">95%</div>
                    <div class="text-sm text-gray-600 md:text-base">Pago Puntual</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
