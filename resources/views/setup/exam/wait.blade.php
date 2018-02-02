@extends('layouts.pcu')

@section('title', 'Prueba finalizada')

@section('content')
    <div class="container">
        <br>
        @include('setup.breadcrumb')
        <br>
        <h5>Hemos recibido tus respuestas</h5>
        <div class="card-panel">
            <p><b><i class="mdi mdi-check-circle green-text"></i> La prueba ha finalizado.</b></p>
            <p>Será revisada por varios correctores y te notificaremos de su decisión.</p>
            <p>Mientras tanto, ¡no hay nada que hacer!</p>
        </div>
        <p>Preguntas frecuentes</p>
        <div class="card-panel">
            <p>
                <b>¿Cuánto tardan en corregir la prueba?</b>
                <br>
                Normalmente, unas 24-48 horas.
            </p>
            <p>
                <b>¿Cómo me enteraré cuanto se sepa mi nota?</b>
                <br>
                No decimos la nota, aunque sí decimos si has aprobado o no.
                <br>
                Si has activado el correo electrónico anteriormente, te enviaremos un mensaje. Si no, por esta misma web.
            </p>
            <p>
                <b>¿Qué pasa si suspendo?</b>
                <br>
                Si es la primera vez, no te preocupes. Sin embargo, ten cuidado: la prueba solo se puede hacer tres veces.
                <br>> Si suspendes una tercera vez, no podrás entrar a PoPLife. <
            </p>
            <p>
                <b>¿Hay revisión de la prueba?</b>
                <br>
                No permitimos revisar la corrección.
                <br>Supervisamos constantemente la imparcialidad de los correctores para asegurar la calidad de las correcciones.
                <br>Si crees que se ha cometido una injusticia, puedes ponerte en contacto con un Administrador.
            </p>
            <p>
                <b>¿Después de aprobar podré entrar a PoPLife?</b>
                <br>
                La prueba no es el último paso. Todavía tendrás que pasar una entrevista oral.
            </p>
            <p>
                <b>¿Cuántos intentos me quedan?</b>
                <br>
                Te quedan {{ Auth::user()->getExamTriesRemaining() }} intentos todavía, sin contar este.
            </p>
            <p>
                <b>¡Tengo una duda que no aparece aquí!</b>
                <br>
                Te invitamos a usar nuestro servicio de soporte.
                <br>Haz clic en el botón azul de abajo a la derecha de la página.
            </p>
        </div>
    </div>
@endsection