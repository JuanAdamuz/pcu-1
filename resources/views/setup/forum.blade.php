@extends('layouts.pcu')

@section('title', 'Enlace al foro')

@section('content')
    <div id="app">
        <div class="container">
            <br>
            @include('setup.breadcrumb')
            <br>
            <h5>Enlazar la cuenta del foro</h5>
            <div v-if="!answered">
                @if(!Auth::user()->imported)
                    ¡Enhorabuena por pasar la prueba escrita! Ahora queda lo fácil.
                @else
                    ¡Último paso y te dejamos en paz!
                @endif
                <div class="card-panel">
                    <p>
                        <b>¿Tienes una cuenta en <a href="http://plataoplomo.wtf/forum">nuestro foro</a>?</b>
                        <br>
                        <small>Si respondes que no, te explicaremos dónde y cómo registrarte.</small>
                    </p>
                    <p>
                        <a @click.prevent="answered = true; registered = false" class="btn white black-text waves-effect">No</a>
                        <a @click.prevent="answered = true; registered = true" class="btn white blue-text waves-effect">Sí</a>
                    </p>
                </div>
            </div>
            <div v-if="answered" v-cloak>
                <div v-if="registered">
                    <div class="card-panel">
                        <p>
                            Haz clic para enlazar tu cuenta del foro
                            <br>
                            <small>Tendrás que iniciar sesión con tu cuenta.</small>
                            <p>
                            <a href="{{ route('setup-forum-socialite-redirect') }}" class="btn green waves-effect"><i class="material-icons left">link</i> Enlazar la cuenta del foro</a>
                        </p>
                        <p>
                            <small>
                                <a @click.prevent="answered = true; registered = false" href="#">¡Ups!, parece que no me he registrado</a>
                            </small>
                        </p>
                        </p>
                    </div>
                </div>
                <div v-if="!registered">
                    <p>A continuación te explicamos cómo registrarte en nuestro foro.</p>
                    <div class="card-panel">
                        <b>1. Accede a la página de registro</b>
                        <p>Regístrate en nuestro foro usando el formulario de registro.</p>
                        <p><b>Usa el nombre que hayas elegido parra jugar como nombre de usuario</b>.</p>
                        <a target="_blank" href="http://plataoplomo.wtf/forum/index.php?/register/" class="btn white blue-text waves-effect">Abrir registro <i class="material-icons right">open_in_browser</i></a>
                        <br><small>El registro se abrirá en una nueva pestaña.</small>
                    </div>
                    <div class="card-panel">
                        <b>2. Verifica tu cuenta</b>
                        <p>Verifica tu cuenta siguiendo las instrucciones.</p>
                        <small>Podrías tener que verificar tu correo.</small>
                    </div>
                    <div class="card-panel">
                        <b>3. Enlaza tu cuenta del foro</b>
                        <p>Para comprobar que ya te has registrado, enlaza tu cuenta.</p>
                        <a href="#" @click.prevent="answered = true; registered = true;" class="btn blue waves-effect">Enlazar <i class="material-icons right">link</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.0/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                answered: false,
                registered: false,
            }
        });
    </script>
@endsection