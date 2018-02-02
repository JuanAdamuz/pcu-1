@extends('layouts.pcu')

@section('title', 'Preferencias de correo electrónico')

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>Correo</h5>
            <p>¿Activar correo electrónico?</p>
            <div v-if="!chosen">
                <div class="row">
                    <div class="col l6 s12">
                        <div class="card-panel">
                            <form @submit.prevent="enable()" v-if="!verify">
                                <div class="input-field">
                                    <input :disabled="loading || verify" type="email" name="email" v-model="email" required>
                                    <label for="email">Dirección de correo electrónico <span class="red-text">*</span></label>
                                </div>
                                <button v-if="! loading" type="submit" class="btn blue waves-effect">Enviar correo de verificación</button>
                                <div v-if="loading" class="progress">
                                    <div class="indeterminate"></div>
                                </div>
                                @{{ errors.email }}
                                <br>
                                <br>
                                <div class="divider"></div>
                                <br>
                                <b>Ventajas</b>
                                <ul>
                                    <li>Acceso al foro de POPLife</li>
                                    <li>Avisos cuando haya novedades sobre tu proceso de certificación</li>
                                    <li>Recibe notificaciones cuando no estés en la página</li>
                                    <li>Recibe avisos cuando haya cambios en tu cuenta</li>
                                </ul>
                                <small>
                                    No compartiremos el correo con terceros.
                                    <br>
                                    Te puedes dar de baja del servicio fácilmente cuando quieras.
                                </small>
                            </form>
                            <form @submit.prevent="verifyCode()" v-if="verify">
                                <small>Correo electrónico:</small>
                                <br><b>@{{ email }}</b>
                                <p>Te hemos enviado un mensaje con un enlace.
                                    <br><b>Haz clic en el enlace del mensaje</b> y recarga esta página.</p>
                                <div v-if="loading">
                                    <br>
                                    <div class="progress">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col l6 s12">
                        <div class="card-panel">
                            <p>Si lo prefieres, no hace falta que lo actives.</p>
                            <p>Podrás activarlo en cualquier momento en tus ajustes.</p>
                            <a :disabled="loading" @click.prevent="disable()" class="btn white black-text waves-effect">No activar por ahora</a>
                            <br><small>No tener un correo añadido limitará las funciones de tu cuenta.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chosen && email_enabled">
                <div class="card-panel">
                    <p>Proceso de verificación aquí.</p>
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
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var app = new Vue({
            el: '#app',
            data: {
                chosen: false,
                email_enabled: false,
                email: '{{ is_null(Auth::user()->email) ? "" : Auth::user()->email }}',
                loading: false,
                verify: {{ !is_null(Auth::user()->email_enabled) && Auth::user()->email_enabled && !is_null(Auth::user()->email) ? "true" : "false" }},
                verified: false,
                errors: {},
                code: '',
                taken: false,
                changed: true,
            },
            methods: {
                enable: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-email') }}', {
                            email: this.email,
                            enable: true
                        })
                        .then(function(response) {
                            app.loading = false;
                            if(response.data === "verify") {
                                app.verify = true;
                                app.verified = false;
                            }
                            if(response.data === "next") {
                                app.loading = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                        if(error.response.status === 422) {
                            this.errors = error.response.data;
                            Materialize.toast(this.errors.email, 4000);
                        }
                        });
                },
                verifyCode: function() {
                    app.loading = true;
                    axios.get('{{ route('setup-email') }}', {
                        email: this.email,
                        enable: true
                    })
                        .then(function(response) {
                            app.loading = false;
                            Materialize.toast(response.data, 4000);
                            if(response.data === "next") {
                                app.chosen = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                        if(error.response.status === 422) {
                            this.errors = error.response.data;
                            Materialize.toast(this.errors.email, 4000);
                        }
                    });
                },
                disable: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-email') }}', {
                        enable: false
                    })
                        .then(function(response) {
                            app.loading = true;
                            if(response.data === "next") {
                                app.chosen = true;
                                window.location.replace("/setup/name");
                            }
                        }).catch(function(error) {
                        app.loading = false;
                    });
                }
            },
            watch: {
                email: function() {
                    app.changed = true;
                }
            }
        });
    </script>
@endsection