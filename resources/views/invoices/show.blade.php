<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl p-8 mx-auto">
        <h2 class="mb-6 text-xl font-semibold">Factura #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</h2>

        <!-- Invoice Details -->
        <div class="grid gap-8 mb-8 md:grid-cols-2">
            <div>
                <h3 class="mb-3 text-lg font-semibold text-blue-900">Información de la Factura</h3>
                <p><span class="font-semibold text-gray-700">Fecha de Emisión:</span> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-gray-700">Fecha de Vencimiento:</span> {{ $invoice->due_date->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-gray-700">Período de Facturación:</span> {{ $invoice->billing_period }}</p>
                <p><span class="font-semibold text-gray-700">Estado:</span>
                    @if($invoice->status === 'pending') Pendiente de Pago
                    @elseif($invoice->status === 'waiting_review') En Revisión
                    @elseif($invoice->status === 'paid') Pagado
                    @elseif($invoice->status === 'overdue') Vencido
                    @endif
                </p>
            </div>
            <div>
                <h3 class="mb-3 text-lg font-semibold text-blue-900">Datos del Miembro</h3>
                <p><span class="font-semibold text-gray-700">Nombre:</span> {{ $invoice->user->name }}</p>
                <p><span class="font-semibold text-gray-700">Email:</span> {{ $invoice->user->email }}</p>
                <p><span class="font-semibold text-gray-700">Plan:</span>
                    @if($invoice->user->plan === 'full') Plan Completo @else Plan Básico @endif
                </p>
            </div>
        </div>

        <!-- Amount -->
        <div class="p-6 mb-8 text-center border-l-4 border-blue-500 rounded-lg bg-slate-100">
            <div class="mb-2 text-4xl font-bold text-blue-900">${{ number_format($invoice->amount, 0, ',', '.') }}</div>
            <p class="font-semibold">{{ $invoice->description }}</p>
        </div>

        <!-- Payment Instructions -->
        @if($invoice->status === 'pending')
            <div class="p-6 mb-8 border rounded-lg bg-amber-50 border-amber-500">
                <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-amber-800">💰 Instrucciones de Pago</h3>

                <div class="p-4 mb-4 bg-white border-l-4 rounded-lg border-amber-500">
                    <h4 class="mb-2 font-semibold text-blue-900">Paso 1: Realizar la Transferencia</h4>
                    <p>Transfiera al siguiente CBU:</p>

                    <div class="flex items-center gap-2 mt-2">
                        <div id="cbu-code" class="px-3 py-2 font-mono tracking-wide text-white bg-blue-900 rounded select-all">
                            3220001805000013390012
                        </div>
                        <button
                            onclick="copyCBU()"
                            class="px-3 py-2 text-sm font-semibold text-white transition rounded bg-amber-500 hover:bg-amber-600">
                            Copiar
                        </button>
                    </div>

                    <!-- Mensaje oculto -->
                    <p id="copy-message" class="hidden mt-2 text-sm font-medium text-green-600">✅ Copiado al portapapeles</p>

                    <p class="mt-2 font-semibold">Cuenta Corriente NO PROPIA</p>
                </div>

                <div class="p-4 bg-white border-l-4 rounded-lg border-amber-500">
                    <h4 class="mb-2 font-semibold text-blue-900">Paso 2: Incluir el Número de Referencia</h4>
                    <p>Debe poner el siguiente número:</p>

                    <div class="flex items-center gap-2 mt-2">
                        <div id="reference-code" class="px-3 py-2 font-mono tracking-wide text-white bg-blue-900 rounded select-all">
                            1054747110
                        </div>
                        <button
                            onclick="copyReference()"
                            class="px-3 py-2 text-sm font-semibold text-white transition rounded bg-amber-500 hover:bg-amber-600">
                            Copiar
                        </button>
                    </div>

                    <!-- Mensaje oculto -->
                    <p id="reference-message" class="hidden mt-2 text-sm font-medium text-green-600">✅ Copiado al portapapeles</p>

                    <div class="p-3 mt-3 font-semibold text-red-800 bg-red-100 border border-red-400 rounded">
                        ⚠️ MUY IMPORTANTE: Sin este número no podremos identificar su pago.
                    </div>
                </div>

                <script>
                    function copyReference() {
                        const refText = document.getElementById('reference-code').innerText;
                        navigator.clipboard.writeText(refText).then(() => {
                            const msg = document.getElementById('reference-message');
                            msg.classList.remove('hidden');
                            setTimeout(() => msg.classList.add('hidden'), 2000);
                        });
                    }
                </script>

            </div>

            <!-- Upload Section -->
            <div class="p-6 text-center border rounded-lg bg-sky-50 border-sky-500">
                <h3 class="mb-2 text-lg font-semibold text-sky-900">📤 Subir Comprobante</h3>
                <p>Una vez realizada la transferencia, suba el comprobante para procesar su pago:</p>

                @if(session('success'))
                    <div class="p-3 mt-4 border rounded-lg bg-emerald-100 border-emerald-500 text-emerald-800">✅ {{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="p-3 mt-4 text-red-800 bg-red-100 border border-red-500 rounded-lg">❌ {{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="p-3 mt-4 text-left text-red-800 bg-red-100 border border-red-500 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!$invoice->receipt_path)
                    <form action="{{ route('invoices.upload-receipt', $invoice) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                        @csrf
                        <input type="file" name="receipt" accept="image/*,.pdf" required class="block w-full p-2 text-sm border rounded">
                        <button type="submit" class="px-6 py-2 font-semibold text-white rounded-lg bg-sky-600 hover:bg-sky-700">Subir Comprobante</button>
                    </form>
                @else
                    <div class="p-3 mt-4 border rounded-lg bg-sky-100 border-sky-500 text-sky-900">
                        📄 <strong>Comprobante subido:</strong> Está siendo procesado. Recibirá confirmación por email.
                        <div class="mt-2">
                            <a href="{{ route('invoices.show-receipt', $invoice) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                👁️ Ver Comprobante
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @elseif($invoice->status === 'waiting_review')
            <div class="p-6 mb-8 text-center border rounded-lg bg-orange-100 border-orange-500">
                <h3 class="mb-2 text-lg font-semibold text-orange-800">⏳ Comprobante En Revisión</h3>
                <p class="text-orange-700 mb-4">Su comprobante de pago está siendo revisado por nuestro equipo. Recibirá una confirmación por email una vez procesado.</p>
                @if($invoice->receipt_path)
                    <div class="mt-4">
                        <a href="{{ route('invoices.show-receipt', $invoice) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                            👁️ Ver Comprobante Subido
                        </a>
                    </div>
                @endif
            </div>
        @elseif($invoice->status === 'paid')
            <div class="p-6 mb-8 text-center border rounded-lg bg-emerald-100 border-emerald-500">
                <h3 class="mb-2 text-lg font-semibold text-emerald-800">✅ Factura Pagada</h3>
                <p class="text-emerald-700">Su pago fue procesado exitosamente el {{ $invoice->paid_at?->format('d/m/Y') }}.</p>
                @if($invoice->receipt_path)
                    <div class="mt-4">
                        <a href="{{ route('invoices.show-receipt', $invoice) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            👁️ Ver Comprobante de Pago
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Motto -->
        <div class="my-8 italic text-center text-blue-500">
            "Inmare Pro Patria Luctati Honore"<br>
            <small class="block mt-1">En el Mar y Por la Patria Lucharemos con Honor</small>
        </div>
    </div>

    <script>
        function copyCBU() {
            const cbuText = document.getElementById('cbu-code').innerText;
            navigator.clipboard.writeText(cbuText).then(() => {
                const msg = document.getElementById('copy-message');
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            });
        }
    </script>
</x-app-layout>
