<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Admin - Gestión de Encuestas') }}
            </h2>
            <a href="{{ route('admin.polls.create') }}" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Crear Encuesta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="p-4 mb-6 rounded-lg bg-gray-50">
                        <form method="GET" class="flex flex-wrap items-end gap-4">
                            <div class="flex-1 min-w-64">
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       placeholder="Buscar por título"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="status" id="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicada</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.polls.index') }}" class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Limpiar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Polls Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Título</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Preguntas</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Respuestas</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Fecha Publicación</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($polls as $poll)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $poll->title }}</div>
                                            @if($poll->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($poll->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $poll->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $poll->status === 'published' ? 'Publicada' : 'Borrador' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {{ $poll->questions_count }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            @if($poll->status === 'published')
                                                <a href="{{ route('admin.polls.results', $poll) }}" class="text-blue-600 underline hover:text-blue-900">
                                                    {{ $poll->responses_count }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {{ $poll->published_at ? $poll->published_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.polls.edit', $poll) }}"
                                                   class="px-3 py-1 text-blue-600 rounded hover:text-blue-900 bg-blue-50 hover:bg-blue-100">
                                                    {{ $poll->status === 'draft' ? 'Editar' : 'Ver' }}
                                                </a>

                                                @if($poll->status === 'published')
                                                    <a href="{{ route('admin.polls.results', $poll) }}"
                                                       class="px-3 py-1 text-green-600 rounded hover:text-green-900 bg-green-50 hover:bg-green-100">
                                                        Resultados
                                                    </a>
                                                @endif

                                                @if($poll->status === 'draft')
                                                    <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1 text-red-600 rounded hover:text-red-900 bg-red-50 hover:bg-red-100"
                                                                onclick="return confirm('¿Está seguro de eliminar esta encuesta?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No se encontraron encuestas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $polls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
