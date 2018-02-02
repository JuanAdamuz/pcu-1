@extends('layouts.pcu')

@section('title', 'Bienvenida')

@section('content')
    <div class="container">
        @if($user->imported)
            <div>
                <br>
                <h5>¡Hola!</h5>
                <p>Te damos la bienvenida a este sitio web.</p>
                <div class="card-panel">
                    <b>¿Qué es esto?</b>
                    <p>Esto es lo que llamamos <b>PCU</b> (Panel de Control del Usuario).</p>
                    <p>Desde aquí puedes gestionar y ver <b>tu cuenta de POPlife</b>: nombre, coches, dinero y demás.</p>
                    <p>Además, encontrarás <b>guías, noticias, el changelog, descarga de mods y más</b>.</p>


                    @if($user->imported_exam_exempt)
                        <b>¿Qué tengo que hacer?</b>
                        <p>Simplemente te pediremos <b>unos pocos datos</b>, no tardarás mucho. Si tienes algo mejor que hacer, hazlo. No hay prisa.</p>

                        <b>Hemos importado tus datos del POP4</b>
                        <p>¡Ya estás en la whitelist! No tienes que hacer nada, en principio. Si no, te avisaremos, no te preocupes.</p>
                    @endif
                </div>

                @if(!$user->imported_exam_exempt)
                    <div class="card-panel">
                        <b><i class="material-icons orange-text tiny">warning</i> Todavía no estás en la whitelist</b>
                        <p>Debido a tu historial de sanciones en versiones anteriores de POPLife, <b>tienes que repetir el proceso de whitelist</b> o certificación.</p>
                        @if(isset($user->imported_exam_message))
                            <p>
                                @if($user->imported_exam_message == '72h')
                                    <b>En tu caso</b>, tienes una sanción con una duración igual o mayor a 72 horas.
                                @elseif($user->imported_exam_message == 'Reiterado')
                                    <b>En tu caso</b>, tienes más de una sanción por el mismo motivo. Es decir: cometiste una infracción de forma reincidente.
                                @elseif($user->imported_exam_message == 'Perma')
                                    <b>En tu caso</b>, vienes de haber sido sancionado permanentemente.
                                @else
                                    <b>En tu caso</b>, este es el motivo: "{{ $user->imported_exam_message }}"
                                @endif
                            </p>
                        @endif
                        <small>Si crees que es un error, ponte en contacto con un administrador antes del comienzo del POP5.</small>
                    </div>
                @endif
            </div>
        @else
            <div>
                <br>
                <h5>Te damos la bienvenida</h5>
                <p>¡Hola! Vamos a explicarte lo que tienes que hacer para empezar a jugar.</p>
                <div class="card-panel">
                    <p>Para jugar a PoPLife <b>tienes que pasar un proceso de certificación</b>.</p>
                    <p>Primero comprobaremos que tengas el juego. Luego, te haremos un par de preguntas.</p>
                    <p>Para continuar, tendrás que revisar las normas y hacer una pequeña prueba escrita.</p>
                    <p>Por último, tendremos una entrevista personal contigo y luego podrás jugar con nosotros.</p>
                    <br>
                    <p>Puede parecer complicado y largo, pero nos hemos esforzado por que sea sencillo y llevadero.</p>
                    <p>
                        <b>Cualquier duda puedes preguntárnosla pulsando sobre el <span class="blue-text text-lighten-1">botón de abajo a la derecha</span>.</b>
                    </p>
                    <br>
                    <span><i>~ El equipo de Plata o Plomo ~</i></span>
                </div>
                <div class="card-panel">
                    <b class="green-text"><i class="material-icons tiny">check_circle</i> Plazas abiertas</b>
                    <p>Hay plazas disponibles.</p>
                    <small>Esto podría cambiar mientras realizas el proceso de certificación.</small>
                </div>
            </div>
        @endif
        <div class="card-panel">
            <a href="{{ route('setup-checkgame') }}" class="btn blue waves-effect">Empezar <i class="material-icons right">navigate_next</i></a>
        </div>
    </div>
@endsection