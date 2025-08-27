<x-mail::message>
# Comprobante de Pago Subido

Hemos recibido un comprobante de pago para el fondo de promoción con los siguientes detalles:

- **Nombre:** {{ $invoice->user->name }}
- **Email:** {{ $invoice->user->email }}
- **Monto:** ${{ number_format($invoice->amount, 2) }}
- **Fecha de Factura:** {{ $invoice->invoice_date->format('d/m/Y') }}
- **Fecha de Vencimiento:** {{ $invoice->due_date->format('d/m/Y') }}
- **Período de Facturación:** {{ $invoice->billing_period }}

El comprobante de pago se encuentra adjunto a este correo.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
