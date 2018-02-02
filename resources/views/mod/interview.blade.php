@extends('layouts.pcu')

@section('title', 'Entrevista #' . $exam->id)

@section('content')
    @include('mod.menu')
    <div class="container" id="app">
        <br>
        <h5>Entrevista #{{ $exam->id }}</h5>

        <div class="row">
            <div class="col s12 m4">
                <p>Datos</p>
                <div class="card-panel">
                    <p>
                        <small>SteamID:</small>
                        <br>{{ $exam->user->steamid }}
                    </p>
                    <p>
                        <small>Fecha de nacimiento:</small>
                        <br>
                        @if(is_null($exam->user->birth_date))
                            <i>-</i>
                        @else
                            {{ $exam->user->birth_date->format('d/m/Y') . ' (' . $exam->user->birth_date->age . ' años)' }}
                        @endif
                    </p>
                    <p>
                        <small>Hora inicio entrevista:</small>
                        <br>{{ $exam->interview_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            @if(!is_null($exam->interview_code_at))
                <div class="col s12 m8">
                    <p>Resultados prueba escrita</p>
                    <div class="card-panel">
                        <p>
                            <small>Puntuación:</small>
                            <br><b>{{ $exam->score or 0  }}%</b>
                        </p>
                        <p>
                            <small>Fecha inicio:</small>
                            <br>{{ $exam->start_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <small>Fecha final:</small>
                            <br>@if(!is_null($exam->finish_at))
                                {{ $exam->finish_at->format('d/m/Y H:i') }}
                            @else
                                {{ $exam->end_at->format('d/m/Y H:i') }} <small>(Automáticamente)</small>
                            @endif
                        </p>
                    </div>
                    <p>Detalle prueba escrita</p>
                    <div class="card-panel">
                        @php
                            $questionCount = 1;
                        @endphp
                        @foreach($exam->structure as $group)
                            @foreach($group['questions'] as $question)
                                @php
                                    $questionModel = \App\Question::find($question['id']);
                                    $answer = \App\Answer::find($question['answer_id']);
                                @endphp
                                <a href="#question-{{ $questionCount }}" class="chip @if(!is_null($answer)) @if($answer->score >= 50) green lighten-4 @else red lighten-4 @endif @endif">{{ $questionCount }} </a>
                                @php
                                    $questionCount++;
                                @endphp
                            @endforeach
                        @endforeach

                        <div class="divider" style="margin-top: 8px"></div>
                        @php
                            $questionCount = 1;
                        @endphp
                        @foreach($exam->structure as $group)
                            <div class="divider"></div>
                            <p>
                                <span class="flow-text">{{ $group['name'] }}</span>
                            @foreach($group['questions'] as $question)
                                @php
                                    $questionModel = \App\Question::find($question['id']);
                                    $answer = \App\Answer::find($question['answer_id']);
                                @endphp
                                <p id="question-{{ $questionCount }}">
                                    <span class="chip @if(!is_null($answer)) @if($answer->score >= 50) green lighten-4 @else red lighten-4 @endif @endif">{{ $questionCount }}</span>
                                    <span>{{ $questionModel->question }}</span>
                                    @if(!is_null($answer) && ($questionModel->type == 'text'))
                                        <br>
                                        <code>"{{ $answer->answer }}"</code>
                                        {{--                                        <br><small>{{ $answer->score }}% | {{ $question['value'] }}p</small>--}}
                                    @endif
                                    @if(is_null($answer))
                                        <br>
                                        Sin responder
                                    @endif
                                    @php
                                        $questionCount++;
                                    @endphp
                                </p>
                                @endforeach
                                </p>
                            @endforeach
                    </div>
                </div>
                <div class="col s12">
                    <div class="card-panel">
                        <button :disabled="loading" @click.prevent="grade(false, false)" class="btn red waves-effect"><i class="material-icons left">block</i> Suspender</button>
                        <button :disabled="loading" @click.prevent="grade(false, true)" class="btn white black-text waves-effect"><i class="material-icons left">child_care</i> Menor de 16 años</button>
                        <button :disabled="loading" @click.prevent="grade(true, false)" class="btn green waves-effect"><i class="material-icons left">done</i> Aprobar</button>
                    </div>
                </div>
            @else
                <div class="col s12 m8">
                    <p>Código de seguridad</p>
                    @include('common.errors')
                    <div class="card-panel">
                        <form action="{{ route('mod-interview-code', $exam) }}" method="POST">
                            {{ csrf_field() }}
                            <input name="code" type="text" placeholder="Código proporcionado por el usuario" required minlength="32" maxlength="32" data-length="32" spellcheck="false">
                            <button type="submit" class="btn blue waves-effect">Comprobar</button>
                        </form>
                    </div>
                    <div class="card-panel">
                        <b>Instrucciones:</b>
                        <p>Al usuario le aparecerá un código en su pantalla. <br>Pídele que recargue la página y que te lo pase por chat.</p>
                        <p>El código tiene una longitud de 32 caracteres y no tiene espacios. <br> <small>Ejemplo: <code>mmaUuU5x1PdFLzUi0maUuU5x1PdFLzUi</code></small></p>
                        <p>Este paso es necesario para garantizar que el usuario es quien dice ser y no otra persona.</p>
                    </div>
                </div>
            @endif
            <div class="col s12">
                <form onsubmit="return confirm('¿Cancelar entrevista? @if(!is_null($exam->interview_code_at)) \nEl usuario será notificado. @endif')" action="{{ route('mod-interview-cancel', $exam) }}" method="POST">
                    {{ csrf_field() }}
                    <button class="btn-flat waves-effect red-text right">Cancelar entrevista</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                loading: false
            },
            methods: {
                grade: function(pass, pegi) {
                    this.loading = true;
                    axios.post('{{ route('mod-interview-grade', $exam) }}', {
                        pass: pass,
                        pegi: pegi
                    }).then(function() {
                        window.location.replace('{{ route('mod-user', $exam->user) }}');
                    }).catch(function() {
                        {{--window.location.replace('{{ route('mod-user', $exam->user) }}');--}}
                    });
                }
            }
        });
    </script>
@endsection