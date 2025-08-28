<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio: Facturas Pendientes - Promoción 138</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: #fbbf24;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #1e3a8a;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .invoice-item {
            background: #fef2f2;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ef4444;
            border: 1px solid #fecaca;
        }
        .invoice-item.overdue {
            background: #fee2e2;
            border-left-color: #dc2626;
            border-color: #fca5a5;
        }
        .invoice-item.waiting-review {
            background: #fef3c7;
            border-left-color: #f59e0b;
            border-color: #fed7aa;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
        }
        .amount.waiting-review {
            color: #d97706;
        }
        .button {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background: #b91c1c;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .naval-motto {
            font-style: italic;
            color: #3b82f6;
            text-align: center;
            margin: 15px 0;
        }
        .summary-box {
            background: #fee2e2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #fca5a5;
            text-align: center;
        }
        .overdue-badge {
            background: #dc2626;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .waiting-badge {
            background: #d97706;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">138</div>
        <h1>Promoción 138</h1>
        <p>Escuela Naval Militar</p>
    </div>

    <div class="content">
        <h2>Estimado/a {{ $user->name }},</h2>

        <p>Le recordamos que tiene <strong>{{ $pendingInvoices->count() }} factura(s) pendiente(s)</strong> en el sistema de pagos de la Promoción 138.</p>

        <div class="summary-box">
            <h3 style="color: #dc2626; margin-top: 0;">📋 Resumen de Facturas Pendientes</h3>
            <p style="margin-bottom: 0; font-size: 18px;">
                <strong>Total pendiente: ${{ number_format($pendingInvoices->sum('amount'), 0, ',', '.') }}</strong>
            </p>
        </div>

        <h3>Detalle de Facturas:</h3>

        @foreach($pendingInvoices as $invoice)
            @php
                $isOverdue = $invoice->due_date->isPast();
                $isWaitingReview = $invoice->status === 'waiting_review';
            @endphp
            <div class="invoice-item {{ $isOverdue && !$isWaitingReview ? 'overdue' : '' }} {{ $isWaitingReview ? 'waiting-review' : '' }}">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 10px 0;">
                            Factura #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
                            @if($isWaitingReview)
                                <span class="waiting-badge">EN REVISIÓN</span>
                            @elseif($isOverdue)
                                <span class="overdue-badge">VENCIDA</span>
                            @endif
                        </h4>
                        <p style="margin: 5px 0;"><strong>Período:</strong> {{ $invoice->billing_period }}</p>
                        <p style="margin: 5px 0;"><strong>Fecha de Vencimiento:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
                        <p style="margin: 5px 0;"><strong>Concepto:</strong> {{ $invoice->description }}</p>
                        @if($isOverdue && !$isWaitingReview)
                            <p style="margin: 5px 0; color: #dc2626;"><strong>Vencida hace:</strong> {{ $invoice->due_date->diffForHumans() }}</p>
                        @endif
                        @if($isWaitingReview)
                            <p style="margin: 5px 0; color: #d97706;"><strong>Estado:</strong> Esperando revisión de pago</p>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <span class="amount {{ $isWaitingReview ? 'waiting-review' : '' }}">
                            ${{ number_format($invoice->amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('invoices.index') }}" class="button">
                Ver Mis Facturas y Realizar Pagos
            </a>
        </div>

        <div style="background: #fef3c7; padding: 15px; border-radius: 8px; border: 1px solid #f59e0b;">
            <h4 style="color: #92400e; margin-top: 0;">⚠️ Instrucciones Importantes:</h4>
            <ul style="color: #92400e; margin-bottom: 0;">
                <li>Haga clic en el botón anterior para acceder al sistema y ver las instrucciones de pago</li>
                <li>Si ya realizó el pago, suba el comprobante para agilizar la verificación</li>
                <li>En caso de inconvenientes, contacte con la administración</li>
                @if($pendingInvoices->where('status', 'waiting_review')->count() > 0)
                    <li><strong>Facturas en revisión:</strong> Sus pagos están siendo procesados</li>
                @endif
            </ul>
        </div>

        <div class="naval-motto">
            "Inmare Pro Patria Luctati Honore"<br>
            <small>En el Mar y Por la Patria Lucharemos con Honor</small>
        </div>

        <p>Saludos cordiales,</p>
        <p><strong>Promoción 138 - Escuela Naval Militar</strong></p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no responda a esta dirección.</p>
        <p>© {{ date('Y') }} Promoción 138 - Escuela Naval Militar</p>
    </div>
</body>
</html>