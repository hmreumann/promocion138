<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Facturas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="mb-2 text-lg font-medium text-gray-900">Mis Facturas</h3>
                        <p class="text-sm text-gray-600">Historial de cuotas mensuales - Promoción 138</p>
                    </div>

                    @if($invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Factura
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Período
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Monto
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Estado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Vencimiento
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoices as $invoice)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                                            <span class="text-xs font-bold text-blue-800">138</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $invoice->invoice_date->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                                <span class="font-mono">{{ $invoice->billing_period }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                                <span class="text-lg font-bold">${{ number_format($invoice->amount, 2, ',', '.') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($invoice->status)
                                                    @case('pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            Pendiente
                                                        </span>
                                                        @break
                                                    @case('waiting_review')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-orange-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            En Revisión
                                                        </span>
                                                        @break
                                                    @case('paid')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            Pagado
                                                        </span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            Vencido
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                                <div class="{{ $invoice->due_date < now() && $invoice->status === 'pending' ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                                    {{ $invoice->due_date->format('d/m/Y') }}
                                                </div>
                                                @if($invoice->due_date < now() && $invoice->status === 'pending')
                                                    <div class="text-xs text-red-500">
                                                        {{ $invoice->due_date->diffForHumans() }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                   class="mr-3 text-blue-600 hover:text-blue-900">
                                                    Ver
                                                </a>
                                                @if($invoice->status === 'pending')
                                                    @if($invoice->receipt_path)
                                                        <div class="flex flex-col gap-1">
                                                            <span class="text-xs text-orange-600">
                                                                Comprobante subido
                                                            </span>
                                                            <a href="{{ route('invoices.show-receipt', $invoice) }}"
                                                               target="_blank"
                                                               class="text-xs text-blue-600 hover:text-blue-900">
                                                                Ver Comprobante
                                                            </a>
                                                        </div>
                                                    @else
                                                        <a href="{{ route('invoices.show', $invoice) }}#upload"
                                                           class="text-xs text-green-600 hover:text-green-900">
                                                            Pagar
                                                        </a>
                                                    @endif
                                                @elseif($invoice->status === 'waiting_review')
                                                    <div class="flex flex-col gap-1">
                                                        <span class="text-xs text-orange-600">
                                                            En revisión
                                                        </span>
                                                        @if($invoice->receipt_path)
                                                            <a href="{{ route('invoices.show-receipt', $invoice) }}"
                                                               target="_blank"
                                                               class="text-xs text-blue-600 hover:text-blue-900">
                                                                Ver Comprobante
                                                            </a>
                                                        @endif
                                                    </div>
                                                @elseif($invoice->status === 'paid' && $invoice->receipt_path)
                                                    <a href="{{ route('invoices.show-receipt', $invoice) }}"
                                                       target="_blank"
                                                       class="text-xs text-blue-600 hover:text-blue-900">
                                                        Ver Comprobante
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $invoices->links() }}
                        </div>

                        <!-- Summary Statistics -->
                        <div class="grid grid-cols-1 gap-4 mt-8 md:grid-cols-5">
                            <div class="p-4 rounded-lg bg-blue-50">
                                <div class="text-2xl font-bold text-blue-600">{{ $invoices->total() }}</div>
                                <div class="text-sm text-blue-600">Total</div>
                            </div>
                            <div class="p-4 rounded-lg bg-yellow-50">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ $invoices->where('status', 'pending')->count() }}
                                </div>
                                <div class="text-sm text-yellow-600">Pendientes</div>
                            </div>
                            <div class="p-4 rounded-lg bg-orange-50">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ $invoices->where('status', 'waiting_review')->count() }}
                                </div>
                                <div class="text-sm text-orange-600">En Revisión</div>
                            </div>
                            <div class="p-4 rounded-lg bg-green-50">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $invoices->where('status', 'paid')->count() }}
                                </div>
                                <div class="text-sm text-green-600">Pagadas</div>
                            </div>
                            <div class="p-4 rounded-lg bg-red-50">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ $invoices->filter(function($invoice) { return $invoice->isOverdue(); })->count() }}
                                </div>
                                <div class="text-sm text-red-600">Vencidas</div>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">No hay facturas generadas</h3>
                            <p class="text-gray-500">Las facturas se generan automáticamente el primer día de cada mes.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
