<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Factura - Promoción 138</title>
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
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
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
        .invoice-details {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .button {
            display: inline-block;
            background: #fbbf24;
            color: #1e3a8a;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background: #f59e0b;
            color: #1e3a8a;
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

        <p>Se ha generado una nueva factura para su cuota mensual de la Promoción 138.</p>

        <div class="invoice-details">
            <h3>Detalles de la Factura</h3>
            <p><strong>Número de Factura:</strong> #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Fecha de Emisión:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
            <p><strong>Fecha de Vencimiento:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            <p><strong>Período de Facturación:</strong> {{ $invoice->billing_period }}</p>
            <p><strong>Concepto:</strong> {{ $invoice->description }}</p>
            <p><strong>Importe:</strong> <span class="amount">${{ number_format($invoice->amount, 0, ',', '.') }}</span></p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $invoiceUrl }}" class="button">
                Ver Factura e Instrucciones de Pago
            </a>
        </div>

        <div style="background: #fef3c7; padding: 15px; border-radius: 8px; border: 1px solid #f59e0b;">
            <h4 style="color: #92400e; margin-top: 0;">📋 Instrucciones Importantes:</h4>
            <p style="margin-bottom: 0; color: #92400e;">
                Para realizar el pago, haga clic en el botón anterior para ver las instrucciones detalladas.
                Es muy importante incluir el código de referencia en la transferencia.
            </p>
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
