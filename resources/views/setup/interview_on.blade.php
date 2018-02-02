@extends('layouts.pcu')

@section('title', 'Entrevista')

@section('content')
    <div class="container" id="app">
        <br>
        <div v-if="!accepted">
            <div class="card-panel">
                <h5>Información sobre la privacidad</h5>
                <p>Para mejorar la calidad del servicio, <b>las entrevistas podrían ser grabadas</b> y revisadas en el momento o posteriormente.</p>
                <p><small>Pulsando en aceptar, entiendes y aceptas que podrías ser grabado durante la realización de la entrevista
                        y que, de negarte, se podría dar por suspendida la misma.
                        Según nuestra <a href="">Política de privacidad</a>, las entrevistas grabadas podrían ser
                        alojadas hasta un máximo de 30 días desde su fecha de subida por norma general, siempre com la máxima
                        discreción y seguridad.</small></p>
                <p>No pulses el botón si no has sido advertido de esto por el entrevistador oralmente.</p>
                <a href="#" @click.prevent="accepted = true" class="btn blue waves-effect">Aceptar y continuar</a>
            </div>
        </div>

        <div v-if="accepted" v-cloak>
            <h5>Entrevista personal</h5>
            <p>Estás siendo entrevistado por {{ !is_null($exam->interviewer->getActiveName()) ? $exam->interviewer->getActiveName() : 'entrevistador #' . $exam->interviewer->id }}.</p>
            @if(is_null($exam->interview_code_at))
                <div class="card-panel">
                    <p>Hola. Te damos la bienvenida. Un entrevistador te atenderá ahora.</p>
                    <p><b>El entrevistador te pedirá un código de seguridad. Es el siguiente:</b></p>
                </div>
                <div class="card-panel">
                    <code class="flow-text">{{ $exam->interview_code }}</code>
                    <a class="btn-flat blue-text waves-effect clickable copy tooltip" data-tooltip="Copiar al portapapeles" data-clipboard-text="{{ $exam->interview_code }}" onclick="Materialize.toast('Copiado al portapapeles',  3000)"><i class="material-icons left">content_copy</i>Copiar</a>
                </div>
            @else
                <div class="card-panel">
                    <p><b>Te están realizando la entrevista personal.</b></p>
                    <p>El entrevistador ha recibido tu código y comprobado que sea correcto.</p>
                </div>
            @endif
            <p class="smallprint">
                <small>
                    <b>Aviso sobre la privacidad:</b><br>
                    Te recordamos que, para la mejora de la calidad del servicio y de las correcciones, nos reservamos el derecho de grabar las entrevistas y almacenarlas en nuestros servidores para posterior o inmediata revisión de las mismas. Así mismo, te informamos que los entrevistados no tienen permitido grabar las entrevistas y que hacerlo podría suponer un incumplimiento de los términos del servicio, finalizando la entrevista y resultando con el bloqueo del usuario.
                    Nos tomamos tu privacidad de forma muy seria, y nos esforzamos por mantener las grabaciones seguras. Se encriptarán y, salvo excepciones, se mantendrá un máximo de 30 días almacenadas. Las grabaciones no podrán ser revisadas nunca por terceros.
                </small>
            </p>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                accepted: false,
            }
        });
    </script>
@endsection