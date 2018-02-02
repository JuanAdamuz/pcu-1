@extends('layouts.pcu')

@section('title', 'Supervisión')

@section('content')
@include('mod.menu')
    <div id="app">
        <div class="container">
            <br>
            <h5>Supervisión</h5>
            <div class="card-panel" v-if="loading && review === null">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>
            <div v-if="done" v-cloak>
                <p>Mensaje del sistema</p>
                <div class="card-panel">
                    <div v-if="streak <= 5">
                        <b>Nada que supervisar.</b>
                        <p>En este momento no hay nada pendiente. Vuelve en un rato.</p>
                    </div>
                    <div v-if="streak > 5">
                        <b>¡Vaya! Se acabó la racha...</b>
                        <p>¡Has acabado con todo el trabajo! Nada más por revisar.</p>
                        <p><b>Total: @{{ streak }}</b></p>
                    </div>
                    <a href="{{ route('mod-dashboard') }}" class="btn white indigo-text waves-effect"><i class="material-icons left">arrow_back</i> Volver</a>
                </div>
            </div>
            <div v-cloak v-if="review != null">
                <div v-if="type === 'answer'">
                    <div class="chip">
                        <i class="chipicon material-icons">format_quote</i>
                        <b>Respuesta</b>
                    </div>
                    <answer :answer="review" v-on:reviewed="next()"></answer>
                </div>
                <div v-if="type === 'name'">
                    <div class="chip">
                        <i class="chipicon material-icons">account_circle</i>
                        <b>Revisión de nombre</b>
                    </div>
                    <name :name="review" v-on:reviewed="next()"></name>
                </div>
            </div>
            {{-- Rachas --}}
            <div class="card-panel" v-cloak v-if="streak > 5">
                <p v-cloak class="orange-text" v-if="streak > 5 && streak < 15">
                    <i class="material-icons tiny">whatshot</i>  Racha de @{{ streak }}
                </p>
                <p v-cloak class="red-text" v-if="streak >= 15 && streak < 30">
                    <i class="material-icons tiny">whatshot</i>  Racha de @{{ streak }}
                </p>
                <p v-cloak class="red-text" v-if="streak >= 30 && streak < 45">
                    <b><i class="material-icons tiny">whatshot</i>  Racha de @{{ streak }}</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 45 && streak < 60">
                    <b><i class="material-icons tiny">whatshot</i>  Racha de @{{ streak }}</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 60 && streak < 75">
                    <b><i class="material-icons tiny">whatshot</i><i class="material-icons tiny">whatshot</i>  Racha de @{{ streak }}</b>
                </p>
                <p v-cloak class="black-text" v-if="streak >= 75 && streak < 100">
                    <b><i class="material-icons red-text tiny">whatshot</i><i class="material-icons orange-text tiny">whatshot</i><i class="material-icons yellow-text tiny">whatshot</i>  Racha de @{{ streak }}</b>
                </p>
                <p v-cloak class="black white-text" v-if="streak >= 100">
                    <b><i class="material-icons red-text tiny">whatshot</i><i class="material-icons orange-text tiny">whatshot</i><i class="material-icons yellow-text tiny">whatshot</i>  Racha de @{{ streak }}</b>
                </p>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/js/countdown.min.js"></script>
<script src="/js/moment-countdown.min.js"></script>
<script src="/js/estimate.min.js"></script>
<script type="text/x-template" id="answer-review-template">
    <div>
        <div v-if="!loading">
            <button @keyup.alt.65="review(100)" :disabled="timeout || loading || reporting" @click.prevent="review(100)" class="btn green waves-effect tooltipped" data-tooltip="Bien"><i class="material-icons">thumb_up</i></button>
            <button v-if="!simple" :disabled="timeout || loading || reporting" @click.prevent="review(50)" class="btn yellow black-text waves-effect tooltipped" data-tooltip="Regular">~</button>
            <button :disabled="timeout || loading || reporting" @click.prevent="review(0)" class="btn orange waves-effect tooltipped" data-tooltip="Mal"><i class="material-icons">thumb_down</i></button>
            <span v-if="hasAbuse">|</span>
            <button v-if="hasAbuse" :disabled="timeout || loading || reporting" @click.prevent="reporting = true" class="btn red waves-effect tooltipped" data-tooltip="Suspender el examen"><i class="material-icons">block</i></button>
            <span v-if="timeout && timeoutUnlock">
                <br>
                <small><a href="#" @click.prevent="timeout = false">Tomar una decisión ya</a></small>
            </span>
            <div v-if="reporting" class="card-panel">
                <b>Sancionar usuario</b>
                <br>
                <label for="">Motivo</label>
                <select v-model="select" class="browser-default" name="" id="">
                    <option value="-1" disabled selected>Selecciona un motivo...</option>
                    <option>Responder una burrada en el examen</option>
                    <option>Responder un sin sentido en el examen</option>
                    <option>Dirigirse al corrector en una respuesta</option>
                    <option>Respuesta del examen copiada/plagiada</option>
                    <option>Intentar buguear la página</option>
                    <option value="100">Otro (especificar)</option>
                </select>
                <div style="margin-top: 16px">
                    <textarea required v-if="select == 100" data-length="200" v-model="abuseMessage" class="materialize-textarea" placeholder="Introduce el motivo que verá el usuario..." name="" id="" cols="30" rows="10"></textarea>
                    <button @click.prevent="abuse()" :disabled="loading || select == -1" class="btn red white-text waves-effect"><i class="material-icons left">block</i>Suspender y desactivar</button>
                    <button :disabled="loading" class="btn-flat waves-effect" @click.precent="reporting = false; abuseMessage = null; select = -1">Cancelar</button>
                </div>
            </div>
        </div>
        <div>
            <div v-if="loading">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div id="modal-abuse" class="modal">
            <div class="modal-content">
                <h4>Suspender prueba y reportar abuso</h4>
                <p>Si una respuesta infringe la normativa, márcala como abusiva.</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="answer-template">
    <div class="card-panel">
        <div class="row">
            <div class="col s12 m6">
                <span v-if="answer.question.question != undefined">
                <b>@{{ answer.question.question }}</b>
                </span>
                <span v-if="answer.answer != undefined">
                <br>
                    <p>"<span v-html="answer.answer"></span>"</p>
                </span>
            </div>
            <div class="col s12 m6">
                <answer-review :data-provided="answer.answer" v-on:reviewed="itemReviewed($event)" type="answer" :has-abuse="true" :simple="false" :answer-id="answer.id"></answer-review>
            </div>
            <div class="col s12">
                <div class="row">
                    <div class="col m4 s12" v-for="review in answer.reviews">
                        <div v-if="review.abuse">
                            <div class="card-panel red white-text">
                                <b>Reporte por abuso</b>
                                <p>"@{{ review.abuse_message }}"</p>
                                @{{ review.user.username }}
                            </div>
                        </div>
                        <div v-if="!review.abuse && review.score == 100">
                            <div class="card-panel green lighten-4">
                                <b>@{{ review.score }}%</b>
                                <p>@{{ review.user.username }}</p>
                            </div>
                        </div>
                        <div v-if="!review.abuse && review.score < 100 && review.score > 0">
                            <div class="card-panel yellow lighten-4">
                                <b>@{{ review.score }}%</b>
                                <p>@{{ review.user.username }}</p>
                            </div>
                        </div>
                        <div v-if="!review.abuse && review.score == 0">
                            <div class="card-panel orange lighten-4">
                                <b>0%</b>
                                <p>@{{ review.user.username }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <p>Motivo de la supervisión: <code>@{{ answer.needs_supervisor_reason }}</code></p>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="name-template">
    <div>
        <div class="card-panel">
            <div class="row">
                <div class="col s12 m6">
                    <span class="flow-text">"@{{ name.name }}"</span>
                </div>
                <div class="col s12 m6">
                    <answer-review :data-provided="name.name" v-on:reviewed="itemReviewed($event)" type="name" :has-abuse="false" :simple="true" :answer-id="name.id"></answer-review>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    Vue.component('name', {
        template: '#name-template',
        props: {name: {required: true}},
        methods: {
            itemReviewed: function() {
                this.$emit('reviewed');
            }
        }
    })

    Vue.component('answer', {
        template: '#answer-template',
        props: {answer: {required: true}},
        data: function() {
            return {};
        },
        methods: {
            itemReviewed: function() {
                this.$emit('reviewed');
            }
        }
    });


    Vue.component('answer-review', {
        template: '#answer-review-template',
        props: {answerId: {type: Number, required: true}, type: {required: true}, hasAbuse: {required: true}, simple: {required: true}, dataProvided: {required: true}},
        data: function() {
            return {
                loading: true,
                reporting: false,
                select: -1,
                abuseMessage: null,
                timeout: true,
                timeoutUnlock: false,
            }
        },
        methods: {
            review: function(score) {
                var vm = this;
                if(vm.loading) {
                    return;
                }
                vm.loading = true;
                axios.post('{{ route('mod-supervise') }}', {
                    type: vm.type,
                    id: vm.answerId,
                    score: score,
                    abuse: false,
                })
                .then(function(response) {
                    vm.loading = false;
                    vm.reporting = false;
                    vm.abuseMessage = null;
                    vm.select = -1;
                    vm.$emit('reviewed');
                }).catch(function(error) {
                    vm.loading = false;
                    vm.reporting = false;
                    vm.abuseMessage = null;
                    vm.select = -1;
                    vm.$emit('reviewed');
                });
            },
            abuse: function() {
                var vm = this;
                vm.loading = true;
                axios.post('{{ route('mod-supervise') }}', {
                    type: vm.type,
                    id: vm.answerId,
                    score: 0,
                    abuse: true,
                    abuseId: vm.select,
                    abuseMessage: vm.abuseMessage,
                })
                    .then(function(response) {
                        vm.loading = false;
                        vm.reporting = false;
                        vm.abuseMessage = null;
                        vm.select = -1;
                        Materialize.toast('Usuario sancionado correctamente', 3000);
                        vm.$emit('reviewed');
                    }).catch(function(error) {
                        vm.loading = false;
                        vm.reporting = false;
                        vm.abuseMessage = null;
                        vm.select = -1;
                        vm.$emit('reviewed');
                });
            }
        },
        mounted: function() {
            var vm = this;
            setTimeout(function() {
                vm.loading = false;
                setTimeout(function() {
                    vm.timeoutUnlock = true;
                }, 15000, vm);
                setTimeout(function() {
                    vm.timeout = false;
                }, estimate.text(vm.dataProvided) * 1000, vm);
            }, 500, vm);
        }

    });

    var app = new Vue({
        el: '#app',
        components: ['answer-review'],
        data: {
            loading: true,
            type: 'Revisión',
            review: null,
            streak: 0,
            done: false
        },
        methods: {
            load: function() {
                axios.get('{{ route('mod-supervise-get') }}')
                .then(function(response) {
                    app.loading = true;
                    var object = response.data;
                    if(object.length === 0) {
                        app.loading = false;
                        app.review = null;
                        app.done = true;
                        return;
                    }
                    if(_.first(_.keys(object)) === 'exam') {
                        app.type = 'Prueba';
                        var object = object.exam;
                        app.review = object;
                    }
                    if(_.first(_.keys(object)) === 'answer') {
                        app.type = 'answer';
                        var object = object.answer;
                        app.review = object;
                    }
                    if(_.first(_.keys(object)) === 'name') {
                        app.type = 'name';
                        var object = object.name;
                        app.review = object;
                    }
                    app.loading = false;
                }).catch(function(error) {
                    app.loading = false;
                    Materialize.toast(error.response.data, 4000);
                });
            },
            next: function() {
                var vm = this;
                vm.review = null;
                vm.loading = true;
                vm.streak++;
                vm.load();
            },
        },
        created: function() {
            this.load();
        },
        ready: function() {
            var vm = this;
            window.addEventListener('keyup', function(event) {
                // If down arrow was pressed...
                if (event.keyCode == 65) {
                    vm.$broadcast('a-key-pressed');
                }
            });
        }
    });
</script>
@endsection