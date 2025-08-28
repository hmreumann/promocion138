<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - Promoción 138</title>
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
            background: linear-gradient(135deg, #10b981, #059669);
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
        .success-box {
            background: #d1fae5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #a7f3d0;
            text-align: center;
        }
        .info-box {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0ea5e9;
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
        .timestamp {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 14px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">138</div>
        <h1>Promoción 138</h1>
        <p>Sistema de Pagos - Test Email</p>
    </div>

    <div class="content">
        <h2>✅ Email Configuration Test</h2>

        <div class="success-box">
            <h3 style="color: #065f46; margin-top: 0;">¡Email enviado exitosamente!</h3>
            <p style="margin-bottom: 0; color: #065f46;">
                Si estás leyendo este mensaje, significa que la configuración de email del sistema está funcionando correctamente.
            </p>
        </div>

        <div class="info-box">
            <h4 style="color: #0c4a6e; margin-top: 0;">📧 Información del Email:</h4>
            <ul style="color: #0c4a6e; margin-bottom: 0;">
                <li><strong>Sistema:</strong> Promoción 138 - Laravel {{ app()->version() }}</li>
                <li><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i:s') }}</li>
                <li><strong>Timezone:</strong> {{ config('app.timezone') }}</li>
                <li><strong>Environment:</strong> {{ app()->environment() }}</li>
            </ul>
        </div>

        <div class="timestamp">
            <strong>Timestamp:</strong> {{ now()->toISOString() }}
        </div>

        <p>Este es un email de prueba generado automáticamente por el comando <code>php artisan email:test</code>.</p>

        <div class="naval-motto">
            "Inmare Pro Patria Luctati Honore"<br>
            <small>En el Mar y Por la Patria Lucharemos con Honor</small>
        </div>

        <p>Si tienes algún problema con el sistema de emails, verifica la configuración en el archivo <code>.env</code>.</p>
    </div>

    <div class="footer">
        <p>Este es un correo de prueba automático del sistema Promoción 138.</p>
        <p>© {{ date('Y') }} Promoción 138 - Escuela Naval Militar</p>
    </div>
</body>
</html>