@extends('layouts.pcu')
@section('title', 'Examen #' . $exam->id)
@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <small><a href="{{ route('mod-user', $exam->user) }}"><< volver al pertil de {{ $exam->user->username }}</a></small>
        <h5>Examen #{{ $exam->id }}</h5>
        <div class="row">
            <div class="col s12 m4">
                <p>Información</p>
                <div class="card-panel">
                    <p>
                        <small>Estado:</small>
                        <br>
                        @if(is_null($exam->passed))
                            @if($exam->end_at <= \Carbon\Carbon::now() || $exam->finished)
                                <i class="mdi mdi-file-document"></i> Prueba escrita esperando corrección
                            @else
                                <i class="mdi mdi-file-document"></i> Prueba escrita en proceso
                            @endif
                        @elseif(!$exam->passed)
                            <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Prueba escrita suspensa</span>
                        @elseif(is_null($exam->interview_passed))
                            @if($exam->expires_at <= \Carbon\Carbon::now())
                                Expirado {{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                            @elseif(is_null($exam->interview_user_id))
                                <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-clock"></i> Esperando entrevista
                                {{-- TODO botón iniciar entrevista --}}
                            @else
                                @if(is_null($exam->interview_code_at))
                                    <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-code-array"></i> Entrevista en curso, esperando código <small>({{ $exam->interviewer->username }})</small>
                                @else
                                    <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset"></i> Entrevista en curso <small>({{ $exam->interviewer->username }})</small>
                                @endif
                            @endif
                        @elseif($exam->interview_passed)
                            <span class="green-text"><i class="mdi mdi-check-circle"></i> Prueba escrita y entrevista aprobadas</span>
                        @elseif(!$exam->interview_passed)
                            <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset red-text"></i> <span class="red-text">Entrevista suspensa</span>
                        @else
                            <i>Estado desconocido o no válido.</i>
                        @endif
                    </p>
                    <p>
                        <small>Inicio:</small>
                        <br>{{ $exam->start_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Expira:</small>
                        <br>{{ $exam->expires_at->diffForHumans() }} <small>({{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }})</small>
                        {{--<a href="" class="tooltipped" data-tooltip="Extender">+</a>--}}
                    </p>
                    {{--<p>--}}
                        {{--<small>Acciones:</small>--}}
                        {{--<br>--}}
                        {{--<a href="" class="btn white red-text"><i class="material-icons left">delete</i> Eliminar</a>--}}
                        {{--<br><small>Al eliminarlo no se tendrá en cuenta hacia el límite de intentos.</small>--}}
                    {{--</p>--}}
                </div>
            </div>
            <div class="col s12 m8">
                <p>Entrevista</p>
                <div class="card-panel">
                    <p>
                        <small>Estado:</small>
                        <br>

                        @if(is_null($exam->passed))
                            <i class="mdi mdi-clock"></i> Esperando prueba escrita
                        @elseif(!$exam->passed)
                            <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Prueba escrita suspensa</span>
                        @elseif(is_null($exam->interview_passed))
                            @if($exam->expires_at <= \Carbon\Carbon::now())
                                Expirado {{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                            @elseif(is_null($exam->interview_user_id))
                                <i class="mdi mdi-clock"></i> Esperando entrevista
                                {{-- TODO botón iniciar entrevista --}}
                            @else
                                @if(is_null($exam->interview_code_at))
                                    <i class="mdi mdi-code-array"></i> Entrevista en curso, esperando código <small>({{ $exam->interviewer->username }})</small>
                                @else
                                    <i class="mdi mdi-headset"></i> Entrevista en curso <small>({{ $exam->interviewer->username }})</small>
                                @endif
                            @endif
                        @elseif($exam->interview_passed)
                            <span class="green-text"><i class="mdi mdi-check-circle"></i> Entrevista aprobada</span>
                        @elseif(!$exam->interview_passed)
                            <i class="mdi mdi-headset red-text"></i> <span class="red-text">Entrevista suspensa</span>
                        @else
                            <i>Estado desconocido o no válido.</i>
                        @endif
                    </p>
                    @if(!is_null($exam->interview_user_id))
                        <p>
                            <small>Entrevistador:</small>
                            <br>{{ $exam->interviewer->username }}
                        </p>
                    @endif
                    @if(!is_null($exam->interview_at))
                        <p>
                            <small>Código introducido:</small>
                            <br>{{ $exam->interview_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                        </p>
                    @endif
                    @if(!is_null($exam->interview_end_at))
                        <p>
                            <small>Fin entrevista:</small>
                            <br>{{ $exam->interview_end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }} ({{ $exam->interview_end_at->diffInMinutes($exam->interview_at) }} minutos)
                        </p>
                    @endif
                </div>
                <p>Prueba escrita</p>
                <div class="card-panel">
                    <p>
                        <small>Estado:</small>
                        <br>
                        @if(is_null($exam->passed))
                            @if($exam->end_at <= \Carbon\Carbon::now() || $exam->finished)
                                <i class="mdi mdi-file-document"></i> Prueba escrita esperando corrección
                            @else
                                <i class="mdi mdi-file-document"></i> Prueba escrita en proceso
                            @endif
                        @else
                            @if(!$exam->passed)
                                <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Prueba escrita suspensa</span>
                            @else
                                <span class="green-text"><i class="mdi mdi-file-document green-text"></i> Prueba escrita aprobada</span>
                            @endif
                        @endif
                    </p>
                    @permission('mod-exam-answers')
                    @if(!is_null($exam->score))
                        <p>
                            <small>Calificación:</small>
                            <br>{{ $exam->score or "¿?" }}%
                        </p>
                    @endif
                    <div class="divider"></div>
                    <br>
                    @php
                        $questionCount = 1;
                    @endphp
                    @foreach($exam->structure as $group)
                        @foreach($group['questions'] as $question)
                            @php
                                $questionModel = \App\Question::find($question['id']);
                                $answer = \App\Answer::find($question['answer_id']);
                            @endphp
                            <a href="#question-{{ $questionCount }}" class="chip @if(!is_null($answer)) @if(is_null($answer->score)) @elseif($answer->score == 100) green lighten-4 @elseif($answer->score <= 0) orange lighten-4 @else yellow lighten-4 @endif @endif">
                                {{ $questionCount }}

                                @if($answer->question->type == 'text')
                                    <i class="chipicon material-icons">edit</i>
                                @elseif($answer->question->type == 'single')
                                    <i class="chipicon material-icons">more_vert</i>
                                @endif
                            </a>
                            @php
                                $questionCount++;
                            @endphp
                        @endforeach
                        @if(!$loop->last) | @endif
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
                                <span class="chip @if(!is_null($answer)) @if(is_null($answer->score)) @elseif($answer->score == 100) green lighten-4 @elseif($answer->score <= 0) orange lighten-4 @else yellow lighten-4 @endif @endif">
                                    {{ $questionCount }}
                                    @if($answer->question->type == 'text')
                                        <i class="chipicon material-icons">edit</i>
                                    @elseif($answer->question->type == 'single')
                                        <i class="chipicon material-icons">more_vert</i>
                                    @endif
                                </span>
                                <span>{{ $questionModel->question }}</span>
                                @if(!is_null($answer) && ($questionModel->type == 'text'))
                                    <br>
                                    <code>"{{ $answer->answer }}"</code>
                                    {{--                                        <br><small>{{ $answer->score }}% | {{ $question['value'] }}p</small>--}}
                                @elseif(!is_null($answer) && $questionModel->type == 'single')
                                    <br>
                                    <code>"{{ collect($answer->question->options)->where('id', $answer->answer)->first()['text'] }}"</code>
                                @endif
                                @if(is_null($answer))
                                    <br>
                                    Sin responder
                                @endif

                                @permission('mod-exam-answers-reviews')
                                @if($answer->reviews->count() > 0)
                                    <br>
                            @foreach($answer->reviews as $review)
                                <div class="chip @if($review->abuse) red lighten-1 white-text @elseif($review->score == 100) green lighten-4 @elseif($review->score < 100 && $review->score > 0) yellow lighten-4 @elseif($review->score == 0) orange lighten-4 @endif">
                                    {{ $review->score }}% <small>({{ $review->user->username }})</small> @if($review->abuse) <small>{{ $review->abuse_message or "¿?" }}</small> @endif
                                    <i class="chipicon material-icons">@if($review->abuse) block @elseif($review->score == 100) thumb_up @elseif($review->score < 100 && $review->score > 0) ~ @elseif($review->score == 0) thumb_down @endif</i>
                                </div>
                            @endforeach
                            @endif

                            @if(!is_null($answer->supervisor_at))
                                <div class="card-panel red lighten-4">
                                    <b><i class="mdi mdi-clipboard-account"></i> Supervisor:</b>
                                    <br><small>{{ $answer->supervisor->username }}: <code>{{ $answer->supervisor_action }}</code> @if($answer->supervisor_action == 'score') ({{ $answer->score }}%) @elseif($answer->supervisor_action == 'abuse') ({{ $answer->exam->user->disabled_reason }}) @endif
                                        <br>{{ $answer->supervisor_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</small>
                                </div>
                            @endif
                                @endpermission

                            @if(is_null($answer->score))
                            @else
                                <small>{{ $answer->score }}% de {{ $question['value'] }}</small>
                                @endif

                                @php
                                    $questionCount++;
                                @endphp
                                </p>
                                @endforeach
                                </p>
                                @endforeach
                                {{--<div class="divider"></div>--}}
                                {{--@if(is_null($exam->interview_passed))--}}
                                {{--<p>--}}
                                {{--<small>Acciones:</small>--}}
                                {{--<br>--}}
                                {{--<a href="" class="btn white red-text waves-effect">Suspender</a>--}}
                                {{--</p>--}}
                                {{--@endif--}}
                    @endpermission
                </div>
            </div>
        </div>
    </div>
@endsection