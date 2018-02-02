@extends('layouts.pcu')

@section('title', 'Información sobre la entrevista')

@section('content')
    <div id="app">
        <div class="container">
            <br>
            @include('setup.breadcrumb')
            <div>
                <br>
                <h5>¡Último paso!</h5>
                <p>Ahora solo queda la entrevista personal.</p>
                <div class="card-panel">
                    <p><b>Ya casi no queda nada.</b></p>
                    <p>Ahora que has aprobado la prueba escrita, tienes que pasar una corta entrevista personal.</p>
                </div>
                <div class="card-panel">
                    <b>Antes de nada, revísate las normas.</b>
                    <p>Te recomendamos revisarte antes las normas. Si tienes alguna duda, pregúntale al entrevistador.</p>
                    <a href="{{ route('setup-rules') }}" class="btn white blue-text waves-effect"><i class="material-icons left">navigate_before</i> Ver normas</a>
                </div>
                <div class="card-panel">
                    <b>1. Descarga e instala TeamSpeak 3</b>
                    <p>Descárgate el programa de comunicación por voz TeamSpeak 3 (TS3 desde ahora) desde su página oficial e instálalo.</p>
                    <a href="https://www.teamspeak.com/en/downloads.html" class="btn white blue-text waves-effect">Abrir página de descarga <i class="material-icons right">open_in_browser</i></a>
                </div>
                <div class="card-panel">
                    <b>2. Accede al servidor de Plata o Plomo</b>
                    <p>Conéctate a nuestro servidor de TS3, que será en el que harás la entrevista.</p>
                    <a href="{{ config('exam.ts_link') }}" class="btn white blue-text waves-effect">Conectarse <i class="material-icons right">call_made</i></a>
                    <br>
                    <br>
                    <small>
                        Si no te funciona el botón de encima, conéctate a la dirección:
                        <br>
                        <code>ts3.plataoplomo.wtf</code>
                    </small>
                </div>
                <div class="card-panel">
                    <b>3. Cámbiate el nombre en TS3</b>
                    <p>Localízate. Te encontrarás porque tu alias estará <b>resaltado</b>.</p>
                    <p>Haz doble clic sobre él y cámbialo por el nombre que hayas solicitado.</p>
                </div>
                <div class="card-panel">
                    <b>4. Accede a la sala de espera</b>
                    <p>Localiza la sala de espera llamada "{{ config('exam.ts_room_name') }}".</p>
                    <p>Haz doble clic sobre ella para entrar. La contraseña es: <span class="copy tooltipped clickable" data-tooltip="Copiar" data-clipboard-text="{{ config('exam.ts_room_password') }}" onclick="Materialize.toast('Copiada al portapapeles',  3000)"><code>{{ config('exam.ts_room_password') }}</code> <i class="mdi mdi-content-copy tiny blue-text"></i></span></p>
                    <small>Es posible que ya estés en la sala si has usado el botón de arriba para conectarte.</small>
                </div>
                <div class="card-panel">
                    <b>5. Un corrector te moverá para empezar la entrevista</b>
                    <p>Si hay alguno disponible, un corrector te realizará la entrevista.</p>
                    <a href="{{ route('page', ['slug' => 'entrevistadores']) }}" class="btn white blue-text waves-effect"><i class="material-icons left">access_time</i> Ver entrevistadores y sus horarios</a>
                </div>
                <div class="card-panel">
                    <b>6. ¡Ya está!</b>
                    <p>Si no hay ningún problema durante la entrevista, ya podrás instalar los mods y jugar.</p>
                    <p>Ten en cuenta que si suspendes la entrevista podrías tener que repetir la prueba escrita de nuevo.</p>
                </div>
                <p>Si no apruebas la entrevista antes del {{ Auth::user()->getInterviewExam()->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}, tendrás que volver a realizar la prueba escrita.</p>
            </div>
        </div>
    </div>
@endsection