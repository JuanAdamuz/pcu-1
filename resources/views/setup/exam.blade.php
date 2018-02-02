@extends('layouts.pcu')

@section('title', 'Prueba escrita')

@section('content')
    <div id="app">
        <br>
        <form @submit.prevent="subimtAnswer()">
            <div class="container">
                @include('setup.breadcrumb')
                @if($type === 'first')
                    <br>
                    <h5>Prueba escrita</h5>
                    <div class="card-panel" style="margin-top: 16px">
                        <b>La prueba ha comenzado.</b>
                        <p>
                            <small>Tiempo permitido:</small>
                            <br>
                            <span>{{ $exam->end_at->diffInMinutes($exam->start_at) }} minutos</span>
                        </p>
                        <p>
                            <small>Tamaño de la prueba:</small>
                            <br>
                            <span>{{ $exam->getQuestionCount() }} preguntas</span>
                        </p>
                        <p><b>Ojo:</b> al pasar de pregunta no se puede volver a las anteriores.</p>
                    </div>
                @endif

                @if(!is_null($group))

                   <div style="margin-top: 16px">
                       @php
                           $questionCount = 1;
                       @endphp
                       @foreach($exam->structure as $questionGroup)
                           @if(! $loop->first) <span style="margin-right: 5px;" >|</span> @endif
                           @foreach($questionGroup['questions'] as $questionMenu)
                               <div class="chip @if($pageNumber == $questionCount) black white-text @elseif(!is_null($questionMenu['answer_id'])) blue lighten-4 @endif">
                                   {{ $questionCount }}
                               </div>
                               @php
                                   $questionCount++;
                               @endphp
                           @endforeach
                       @endforeach
                   </div>

                    <div class="card-panel" style="margin-top: 16px">
                                <b class="flow-text">{{ $group['name'] }}</b>
                                <p>{{ $group['description'] }}</p>
                    </div>
                @endif
                @if(isset($question))
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="card-panel">
                                <p>
                                    <b>Pregunta {{ $pageNumber }}</b>
                                    <br>
                                    <small>Vale {{ $question['value'] }} {{ $question['value'] != 1 ? "puntos" : "punto" }}.</small>
                                    <br><br>
                                    <small v-if="!writingMessage"><a @click.prevent="writingMessage = true; message = ''" href="#">Informar de un error en la pregunta</a></small>
                                    <span v-cloak v-if="writingMessage">
                                        <small class="red-text">Informar de un error</small>
                                        <input type="text" v-model="message" placeholder="¿De qué quieres informar?">
                                        <span v-if="message != ''" style="padding-bottom: 16px;"><small>El mensaje se enviará cuando pases de página.</small> <br></span>
                                        <a href="" class="waves-effect btn white black-text" @click.prevent="message = ''; writingMessage = false"><i class="material-icons left">cancel</i> Cancelar</a>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col s12 m8">
                            <div class="card-panel">
                                @include('setup.exam.question', ['question' => \App\Question::find($question['id'])])
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m6">
                                    <div class="hide-on-med-and-up" style="margin-top: 16px">
                                    </div>
                                    <b>Termina <span v-cloak>@{{ diff }}</span></b>
                                    <br><small>La prueba se enviará automáticamente al acabarse el tiempo.</small>
                                </div>
                                <div class="col s12 m6">
                                    @if($type == 'first')
                                        <a href="{{ route('setup-exam', ['page' => 1]) }}" class="btn blue waves-effect pulse right">Ir al examen <i class="material-icons right">navigate_next</i></a>
                                    @else
                                        <button v-if="!loading" :disabled="loading" class="btn blue waves-effect right">Guardar y continuar <i class="material-icons right">navigate_next</i></button>
                                        @if($pageNumber == 1)
                                            <div v-if="!loading" class="right">
                                                <small>No se puede volver a una pregunta una vez pasas a la siguiente.</small>
                                            </div>
                                        @endif
                                        <div v-cloak v-if="loading">
                                            <div class="progress">
                                                <div class="indeterminate"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    {{--<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>--}}
    {{--<script src="https://unpkg.com/vue"></script>--}}
    {{--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>--}}
    {{--<script src="/js/countdown.min.js"></script>--}}
    {{--<script src="/js/moment-countdown.min.js"></script>--}}
    <script>
        {{-- Esto es para los que editen client-side --}}
        /*
        Léeme antes de editar el código.
        -----
        No pierdas el tiempo.
        Puedes manipular el cliente, pero no el servidor.
        Por desgracia para ti, tenemos validación server-side.
        Si cambias algo aquí, solo lo verás tú.
        ¿Atrasar la hora de finalización? Peor para ti. No recibiremos tus respuestas.
        Sinceramente, te recomiendo que te centres en el examen, que luego suspendes.
        Si no te sale bien, haber estudiao.

        En cualquier caso, si encuentras algún bug por aquí, busca a Manolo Pérez y coméntaselo.
        Seguro que sabrá recompensártelo ;)
         */
        var app = new Vue({
            el: '#app',
            data: {
                passed: false,
                date: moment.tz('{{ $exam->started_at }}', '{{ config('app.timezone') }}'),
                dateAllow: moment.tz('{{ $exam->end_at }}', '{{ config('app.timezone') }}'),
                load: new Date(),
                countdown: "",
                diff: "",
                now: moment(new Date()),
                answer: null,
                loading: false,
                message: '',
                writingMessage: false,

            },
            methods: {
                update: function() {
                    var self = this;
                    this.diff = moment(new Date()).to(this.dateAllow).toString();
                    this.countdown = moment(this.dateAllow).countdown().toString();
                    this.now = moment(new Date());
                    setTimeout(function(){ self.update() }, 1000) // recursive!
                },
                subimtAnswer: function(force) {
                    if(app.loading) {
                        return;
                    }
                    force = typeof force !== 'undefined' ? force : false;
                    app.loading = true;
                    if(app.answer === null && !force && !confirm("No has respondido. ¿Quieres continuar?")) {
                        app.loading = false;
                        return false;
                    }
                    axios.post('{{ route('setup-exam', ['id' => $pageNumber]) }}',{
                        answer: app.answer,
                        number: {{ $pageNumber }},
                        message: app.message
                    })
                    .then(function(response) {
                        app.loading = true;
                        if(response.data === "next") {
                            window.location.replace("/setup/exam/" + {{ $pageNumber + 1 }});
                        }
                        if(response.data === "end") {
                            window.location.replace("/setup/examwait");
                        }
                    }).catch(function(error) {
                        app.loading = false;
                        Materialize.toast(error.response.data, 4000);
                    });
                }
            },
            computed: {
                allowExam: function() {
                    return this.now > this.dateAllow;
                },
                allowSubmit: function() {
//                    return this.load < this.load.
                },
                timeColor: function() {

                }
            },
            watch: {
                allowExam: function() {
//                    if
                }
            },
            created: function() {
                this.update();
            }

        });
    </script>
@endsection