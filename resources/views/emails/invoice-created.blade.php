@component('mail::message')
# Promoción 138 - Escuela Naval Militar

Estimado/a **{{ $user->name }}**,

Se ha generado una nueva factura para su cuota mensual de la Promoción 138.

## 📋 Detalles de la Factura

**Número de Factura:** #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}  
**Fecha de Emisión:** {{ $invoice->invoice_date->format('d/m/Y') }}  
**Fecha de Vencimiento:** {{ $invoice->due_date->format('d/m/Y') }}  
**Período de Facturación:** {{ $invoice->billing_period }}  
**Concepto:** {{ $invoice->description }}  
**Importe:** **${{ number_format($invoice->amount, 0, ',', '.') }}**

@component('mail::button', ['url' => $invoiceUrl])
Ver Factura e Instrucciones de Pago
@endcomponent

@component('mail::panel')
## 📋 Instrucciones Importantes:

Para realizar el pago, haga clic en el botón anterior para ver las instrucciones detalladas.  
**Es muy importante incluir el código de referencia en la transferencia.**
@endcomponent

---

*"Inmare Pro Patria Luctati Honore"*  
*En el Mar y Por la Patria Lucharemos con Honor*

Saludos cordiales,  
**Promoción 138 - Escuela Naval Militar**

---
<small>Este es un correo automático, por favor no responda a esta dirección.</small>
@endcomponent