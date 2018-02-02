@extends('layouts.pcu')

@section('title', 'Confirmación de inicio de prueba')

@section('content')
    <div class="container">
        <br>
        @include('setup.breadcrumb')
        <br>
        <h5>¿Empezar prueba escrita?</h5>
        <p>Lee antes de continuar.</p>
        <div class="card-panel">
            <p><b>La prueba tiene una duración máxima de {{ config('exam.duration') }} minutos.</b></p>
            <p>Una vez empieces el tiempo empieza a contar y <b>no se puede pausar</b>.</p>
            <p>Las <b>respuestas erróneas restan</b> puntos. Si no sabes una respuesta, no contestes a la pregunta.</p>
            <p><b>Cuando pases una página no podrás volver</b> a las anteriores.</p>
            <p>Tienes intentos limitados. Si no crees que vayas a aprobar, repasa más las normas.</p>
            <p>Insistimos en que <b>si no sabes la respuesta a una pregunta NO CONTESTES</b>, y menos tonterías. Si respondes tonterías o burradas tu cuenta será suspendida y no podrás jugar.</p>
        </div>
        @if(!Auth::user()->imported_exam_exempt && Auth::user()->imported)
            <div class="card-panel">
                <p>Debido a tu historial de sanciones en versiones anteriores de POPLife, <b>tienes que repetir el proceso de whitelist</b> o certificación.</p>
                @if(isset(Auth::user()->imported_exam_message))
                    <p>
                        @if(Auth::user()->imported_exam_message == '72h')
                            <b>En tu caso</b>, tienes una sanción con una duración igual o mayor a 72 horas.
                        @elseif(Auth::user()->imported_exam_message == 'Reiterado')
                            <b>En tu caso</b>, tienes más de una sanción por el mismo motivo. Es decir: cometiste una infracción de forma reincidente.
                        @elseif(Auth::user()->imported_exam_message == 'Perma')
                            <b>En tu caso</b>, vienes de haber sido sancionado permanentemente.
                        @else
                            <b>En tu caso</b>, este es el motivo: "{{ Auth::user()->imported_exam_message }}"
                        @endif
                    </p>
                @endif
                <small>Si crees que es un error, ponte en contacto con un administrador antes del comienzo del POP5.</small>
            </div>
        @endif
        @if(!config('exam.enabled'))
            <div class="card-panel">
                <b>Los exámenes no están activados en este momento.</b>
                <p>Inténtalo de nuevo más tarde.</p>
                <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> Volver a las normas </a>
            </div>
        @else
            <div class="card-panel">
                <div class="row">
                    @if(Auth::user()->hasExamCooldown())
                        <div class="col s12">
                            <b><i class="mdi mdi-information"></i> Todavía no puedes empezar otra prueba.</b>
                            <p>Hace poco que suspendiste, por lo que tienes que esperar.</p>
                            <p><b>Podrás empezar otra el día {{ Auth::user()->hasExamCooldown(true)->setTimezone(Auth::user()->timezone)->format('d/m/Y \a \l\a\s H:i') }}.</b> <small>(hora según {{ Auth::user()->timezone }})</small></p>
                            <p>Hasta entonces, revisa las normas con calma. Cualquier duda, nos preguntas.</p>
                            <p>Te @if(Auth::user()->getExamTriesRemaining() != 1) quedan {{ Auth::user()->getExamTriesRemaining() }} intentos todavía. @else queda solo un intento. @endif </p>
                        </div>
                    @endif
                    <div class="col s12 m6">
                        <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> Volver a las normas </a>
                    </div>
                    <div class="col s12 m6">
                        @if(Auth::user()->hasExamCooldown())
                            <button class="btn blue right" disabled>Empezar la prueba <i class="material-icons right">navigate_next</i></button>
                        @else
                            <form method="POST" action="{{ route('setup-exam') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="right btn blue waves-effect">Empezar la prueba <i class="material-icons right">navigate_next</i></button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <p>Preguntas frecuentes</p>
        <div class="card-panel">
            <p>
                <b>¿Qué pasa si se me cae la conexión o se me cierra el navegador?</b>
                <br>
                Nada, simplemente el tiempo continuará contando. Vuelve a abrirlo o conéctate de nuevo para continuar.
            </p>
            <p>
                <b>¿Qué pasa si suspendo?</b>
                <br>
                Si suspendes, tendrás que repetir la prueba después de esperar un tiempo.
                <br>Cuando suspendas tres veces la prueba,
                no podrás entrar a POPLife.
            </p>
            <p>
                <b>¿Cuántos intentos me quedan?</b>
                <br>
                {{ Auth::user()->getExamTriesRemaining() }} de 3 intentos, contando la prueba que vas a empezar ahora.
            </p>
        </div>
    </div>
@endsection