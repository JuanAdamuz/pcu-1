@extends('layouts.pcu')

@section('title', 'Comprobación del juego')

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>Comprobación Arma 3</h5>
            <p>Antes de continuar, necesitamos saber si tienes el juego.</p>
            <div v-cloak v-if="error">
                <p class="red-text">Ha ocurrido un error. Inténtalo de nuevo más tarde.</p>
            </div>
            <div v-if="!error">
                <p v-if="!checked">Un momento, por favor...</p>
                <div v-if="loading || !checked">
                    <div class="card-panel">
                        <div class="progress">
                            <div class="indeterminate"></div>
                        </div>
                    </div>
                </div>
                <div v-cloak v-if="checked && !purchased && !loading">
                    <div class="card-panel">
                        <b>¿Tienes el perfil privado?</b>
                        <p>Para que podamos ver si tienes el juego, es posible que tengas que ponerte el perfil público momentáneamente.</p>
                        <a href="#" @click.prevent="instructions = true" v-if="!instructions" class="waves-effect btn white blue-text">Ver instrucciones</a>
                        <div v-if="instructions">
                            <small>Instrucciones:</small>
                            <p>
                                <b>1. Accede a tus ajustes</b>
                                <br><span>Ve a <a target="_blank" href="http://steamcommunity.com/profiles/{{ auth()->user()->steamid }}/edit/settings">tus ajustes de privacidad de tu perfil</a>.</span>
                            </p>
                            <p>
                                <b>2. Cambia el estado de tu perfil a Público</b>
                                <br><img src="/img/steampublic.png" height="100">
                            </p>
                            <p>
                                Una vez guardados los cambios, espera un minuto y pulsa recomprobar más abajo.
                            </p>
                        </div>
                    </div>
                    <div class="card-panel">
                        <p>Para jugar a PoPLife <b>hay que tener comprado el juego de Steam <i>Arma 3</i></b>.</p>
                        <p>No hemos podido asegurarnos de que tengas el juego comprado. A continuación te dejamos un widget para que lo compres si no lo tienes.</p>
                    </div>
                    @if(! Agent::is('Windows'))
                        <div class="card-panel red white-text">
                            <b><i class="material-icons tiny">warning</i> POPLife solo está disponible en Windows</b>
                            <p>Por el momento, no es posible jugar en otros sistemas operativos.</p>
                            <small>Aun así, puedes realizar el proceso de la entrevista en donde quieras.</small>
                        </div>
                    @endif
                    <div class="card-panel">
                        <iframe src="https://store.steampowered.com/widget/107410/31539/" frameborder="0" style="width: 100%" height="190"></iframe>
                    </div>
                    <div class="card-panel">
                        <button :disabled="loading|cooldown" @click.prevent="check()" class="btn blue waves-effect"><i class="material-icons left">refresh</i> Recomprobar</button>
                    </div>
                </div>
                <div v-cloak v-if="purchased">
                    <div class="card-panel green white-text">
                        <h5><i class="material-icons left">check_circle</i> Juego comprobado</h5>
                        <p>Hemos comprobado correctamente que tengas el juego comprado.</p>
                        <a href="{{ route('setup-info') }}" class="btn white green-text waves-effect">Continuar <i class="material-icons right">navigate_next</i></a>
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
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var app = new Vue({
            el: '#app',
            data: {
                checked: false,
                loading: false,
                purchased: false,
                error: false,
                cooldown: false,
                instructions: false
            },
            methods: {
                check: function() {
                    this.loading = true;
                    this.cooldown = true;
                    axios.post('{{ route('setup-checkgame') }}', {})
                    .then(function(response) {
                        app.loading = false;
                        app.checked = true;
                        if(response.data === true) {
                            app.purchased = true;
                        }
                        setTimeout(function() { app.cooldown = false}, 5000);
                    }).catch(function() {
                        app.loading = false;
                        app.error = true;
                        setTimeout(function() { app.cooldown = false}, 5000);
                    });
                }
            },
            created: function() {
                this.check();
                console.log('created');
            }
        });
    </script>
@endsection