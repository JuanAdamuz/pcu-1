@extends('layouts.pcu')

@section('title', 'Reglas')

@section('content')
    <div id="app">
        <div class="container">
            <br>
            @include('setup.breadcrumb')
            <br>
            <h5>Reglas del servidor</h5>
            <p>Con las reglas conseguimos que el juego sea justo. Son la base del servidor.</p>


            @if(!is_null($rules))
                <div class="card-panel">
                    {% $rules->content %}
                </div>
            @else
                <div class="card-panel">
                    <b>Normas no disponibles en este momento</b>
                    <p>Inténtalo de nuevo más tarde.</p>
                </div>
            @endif


            @if(! Auth::user()->hasFinishedSetup())
                <div class="card-panel">
                    <p v-if="! allowExam">
                        <b>Estudia un poco las normas y @{{ diff }} podrás seguir.</b>
                        <br>
                        <small>Aprovecha este rato para entender las normas.</small>
                    </p>
                    <p v-if="allowExam">
                        <b>Cuando te veas preparado, continúa a la prueba.</b>
                        <br>
                        <small>Recuerda que es para ver si has entendido las normas.</small>
                    </p>
                    <a :disabled="!allowExam" href="{{ route('setup-exam') }}" class="btn blue waves-effect">Continuar <i class="material-icons right">navigate_next</i></a>
                </div>
            @endif


        </div>
    </div>
@endsection
@section('js')
    {{--<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.0/vue.min.js"></script>--}}
    {{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
    {{--<script src="/js/moment-countdown.min.js"></script>--}}
    <script type="text/x-template" id="item-template">
        <div>
            <b><i class="material-icons" style="vertical-align: middle; padding-right: 8px">@{{ icon }}</i> @{{ title }}</b>
            <p>
                <slot></slot>
            </p>
        </div>
    </script>
    <script>
        Vue.component('rule', {
            template: '#item-template',
            props: {title: {required: true}, icon: {required: true}}
        });
        var app = new Vue({
            el: '#app',
            components: ['rule-item'],
            data: {
                passed: false,
                date: moment.tz('{{ Auth::user()->rules_seen_at }}', '{{ config('app.timezone') }}'),
                dateAllow: moment.tz('{{ Auth::user()->rules_seen_at->addMinutes(30) }}', '{{ config('app.timezone') }}'),
                load: new Date(),
                diff: "",
                now: moment(new Date()),
                notified: false,
            },
            methods: {
                update: function() {
                    var self = this;
                    moment.locale('es');
                    this.diff = moment(new Date()).to(this.dateAllow).toString();
                    this.now = moment(new Date());
                    setTimeout(function(){ self.update() }, 1000)
                },
                check: function (first) {
                    var self = this;
                    if(!first) {
                        axios.get('{{ route('setup-rules-check') }}')
                            .then(function (response) {/**/})
                            .catch(function () {
                                location.reload();
                            });
                    }
                    setTimeout(function(){ self.check() }, 60000)
                }
            },
            computed: {
                allowExam: function() {
                    return this.now > this.dateAllow;
                }
            },
            watch: {
              allowExam: function(newValue) {
                  if(newValue && !this.notified) {
                      this.notified = true;
                      Materialize.toast('Ha pasado ya media hora. ¡Empieza cuando quieras!', 5000);
                  }
              }
            },
            created: function() {
                this.update();
                this.check(true);
            }
        });
    </script>

@endsection