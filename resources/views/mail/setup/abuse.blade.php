@component('mail::message')
# Tu cuenta ha sido desactivada

Te escribimos para informarte de que tu cuenta ha sido desactivada en base a una de las respuestas de tu prueba escrita.

A continuación te dejamos tu respuesta:

@component('mail::panel')
    {{ $answer }}
@endcomponent

El motivo que el supervisor indicó para tu bloqueo es el siguiente:

@component('mail::panel')
    {{ $reason }}
@endcomponent

En principio, las sanciones por este tipo de cosas suelen ser firmes.

Si quieres más información sobre qué hacer ahora visita la página e intenta iniciar sesión.

Atentamente,<br>
{{ config('app.name') }}
@endcomponent
