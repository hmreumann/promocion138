<x-mail::message>
# Comprobante de Pago Subido

Hemos recibido un comprobante de pago para el fondo de promoción con los siguientes detalles:

- **Nombre:** {{ $invoice->user->name }}
- **Email:** {{ $invoice->user->email }}
- **Monto:** ${{ number_format($invoice->amount, 2) }}
- **Fecha de Pago:** {{ $invoice->paid_at->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }} (Argentina)
- **Período de Facturación:** {{ $invoice->billing_period }}
- **Número de Referencia SMSV:** 1054747110

El comprobante de pago se encuentra adjunto a este correo.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
