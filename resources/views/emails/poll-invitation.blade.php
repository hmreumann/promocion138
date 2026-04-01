@component('mail::message')
# Promoción 138 - Escuela Naval Militar

Estimado/a **{{ $user->name }}**,

Se ha publicado una nueva encuesta que requiere su participación.

## 📋 {{ $poll->title }}

@if($poll->description)
{{ $poll->description }}
@endif

@component('mail::button', ['url' => $pollUrl])
Responder Encuesta
@endcomponent

@component('mail::panel')
## 📋 Información Importante:

- Este enlace es único y personal
- Válido hasta: {{ $expiresAt->format('d/m/Y H:i') }}
- Solo puede responder una vez
- No requiere iniciar sesión
@endcomponent

---

*"Inmare Pro Patria Luctati Honore"*

Saludos cordiales,
**Promoción 138 - Escuela Naval Militar**
@endcomponent
