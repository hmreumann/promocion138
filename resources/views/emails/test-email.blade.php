@component('mail::message')
# Promoción 138 - Sistema de Pagos

## ✅ Email Configuration Test

@component('mail::panel')
### ¡Email enviado exitosamente!

Si estás leyendo este mensaje, significa que la configuración de email del sistema está funcionando correctamente.
@endcomponent

## 📧 Información del Email:

- **Sistema:** Promoción 138 - Laravel {{ app()->version() }}
- **Fecha:** {{ now()->format('d/m/Y H:i:s') }}
- **Timezone:** {{ config('app.timezone') }}
- **Environment:** {{ app()->environment() }}
- **Timestamp:** {{ now()->toISOString() }}

Este es un email de prueba generado automáticamente por el comando `php artisan email:test`.

---

*"Inmare Pro Patria Luctati Honore"*  
*En el Mar y Por la Patria Lucharemos con Honor*

Si tienes algún problema con el sistema de emails, verifica la configuración en el archivo `.env`.

---
<small>Este es un correo de prueba automático del sistema Promoción 138.</small>
@endcomponent