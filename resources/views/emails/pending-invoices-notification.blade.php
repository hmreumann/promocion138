@component('mail::message')
# Promoción 138 - Escuela Naval Militar

Estimado/a **{{ $user->name }}**,

Le recordamos que tiene **{{ $pendingInvoices->count() }} factura(s) pendiente(s)** en el sistema de pagos de la Promoción 138.

@component('mail::panel')
## 📋 Resumen de Facturas Pendientes

**Total pendiente: ${{ number_format($pendingInvoices->sum('amount'), 2, ',', '.') }}**
@endcomponent

## Detalle de Facturas:

@foreach($pendingInvoices as $invoice)
@php
    $isOverdue = $invoice->due_date->isPast();
    $isWaitingReview = $invoice->status === 'waiting_review';
@endphp

**Factura #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}**
@if($isWaitingReview)
🟡 **EN REVISIÓN**
@elseif($isOverdue)
🔴 **VENCIDA**
@endif

**Período:** {{ $invoice->billing_period }}
**Fecha de Vencimiento:** {{ $invoice->due_date->format('d/m/Y') }}
**Concepto:** {{ $invoice->description }}
**Importe:** **${{ number_format($invoice->amount, 2, ',', '.') }}**

@if($isOverdue && !$isWaitingReview)
⚠️ **Vencida hace:** {{ $invoice->due_date->diffForHumans() }}
@endif
@if($isWaitingReview)
📋 **Estado:** Esperando revisión de pago
@endif

---

@endforeach

@component('mail::button', ['url' => route('invoices.index')])
Ver Mis Facturas y Realizar Pagos
@endcomponent

@component('mail::panel')
## ⚠️ Instrucciones Importantes:

- Haga clic en el botón anterior para acceder al sistema y ver las instrucciones de pago
- Si ya realizó el pago, suba el comprobante para agilizar la verificación
- En caso de inconvenientes, contacte con la administración
@if($pendingInvoices->where('status', 'waiting_review')->count() > 0)
- **Facturas en revisión:** Sus pagos están siendo procesados
@endif
@endcomponent

---

*"Inmare Pro Patria Luctati Honore"*
*En el Mar y Por la Patria Lucharemos con Honor*

Saludos cordiales,
**Promoción 138 - Escuela Naval Militar**

---
<small>Este es un correo automático, por favor no responda a esta dirección.</small>
@endcomponent
