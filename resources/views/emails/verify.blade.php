@component('mail::message')
# Verifica tu correo

¡Hola! Haz clic en el siguiente botón para verificar el correo:

@component('mail::button', ['url' => route('verify', ['code' => $user->email_verified_token])])
Verificar correo
@endcomponent

Por motivos de seguridad, si no verificas tu correo en 24 horas, tendrás que volver a añadirlo desde tus ajustes.

Atentamente,<br>
El equipo de POPLife
@endcomponent
