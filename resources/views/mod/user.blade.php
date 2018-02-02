@extends('layouts.pcu')

@section('title', '"' . $user->username .'"')

@section('content')
    @include('mod.menu')

    <div class="container" id="app">
        <br>
        <h5><span class="copy tooltipped clickable" data-tooltip="Copiar" data-clipboard-text="{{ $user->username }}" onclick="Materialize.toast('Copiado al portapapeles',  3000)">{{ $user->username }} <i class="mdi mdi-content-copy tiny blue-text"></i></span></h5>
        @include('common.errors')
        <div class="row">
            <div class="col s12 l4">
                <p>Datos</p>
                <div class="card-panel">
                    <p>
                        <small>SteamID:</small>
                        <br><span class="clickable copy tooltipped" data-tooltip="Copiar" data-clipboard-text="{{ $user->steamid }}" onclick="Materialize.toast('Copiado al portapapeles',  3000)">{{ $user->steamid }} <i class="mdi mdi-content-copy tiny blue-text"></i></span>
                    </p>
                    <p>
                        <small>GUID:</small>
                        <br><small class="clickable copy tooltipped" data-tooltip="Copiar" data-clipboard-text="{{ $user->guid }}" onclick="Materialize.toast('Copiado al portapapeles',  3000)">{{ $user->guid }} <i class="mdi mdi-content-copy tiny blue-text"></i></small>
                    </p>
                    @if(!is_null($user->ipb_id))
                        <p>
                            <small>Enlaces:</small>
                            <br><a target="_blank" href="http://plataoplomo.wtf/forum/index.php?/profile/{{ $user->ipb_id }}-pcu/">Perfil del foro <i class="mdi mdi-open-in-new tiny"></i></a></small>
                        </p>
                    @endif
                    @permission('mod-reveal-birthdate')
                    <p>
                        <small>Fecha de nacimiento:</small>
                        <br>
                        @if(is_null($user->birth_date))
                            <i>-</i>
                        @else
                            <reveal endpoint="{{ route('mod-reveal-birhtdate', $user) }}"></reveal>
                        @endif
                    </p>
                    @endpermission
                    @permission('mod-reveal-email')
                    <p>
                        <small>Correo electrónico:</small>
                        <br>

                        @if(is_null($user->email))
                            <i>-</i>
                        @else
                            <reveal endpoint="{{ route('mod-reveal-email', $user) }}"></reveal>
                        @endif
                    </p>
                    @endpermission
                    <p>
                        <small>Tipo de cuenta:</small>
                        <br>
                        @if($user->imported)
                            @if(!$user->imported_exam_exempt)
                                Importada con examen
                            @else
                                <i class="mdi mdi-exit-to-app tiny"></i> Importada
                            @endif
                        @else
                            Normal
                        @endif
                    </p>
                </div>
                @if($user->disabled)
                    <div class="card-panel">
                        <b>Usuario desactivado</b>
                        <br><small>No puede inciar sesión en la web.</small>
                        <p>
                            <small>Motivo:</small>
                            <br><span>{{ $user->disabled_reason or "-" }}</span>
                        </p>
                        <p>
                            <small>Fecha desactivación:</small>
                            <br><span>{{ $user->disabled_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</span>
                        </p>
                    </div>
                @endif
            </div>
            <div class="col s12 l8">
                <p><i class="mdi mdi-passport"></i> Certificación</p>
                @if($user->hasFinishedSetup())
                    <div class="card-panel">
                        <b><i class="mdi mdi-approval tiny green-text"></i> Certificado</b>
                    </div>
                @else
                    <div class="card-panel">
                        @if($user->getSetupStep() == 0)
                            Comprobación juego
                        @elseif($user->getSetupStep() == 1)
                            Datos
                        @elseif($user->getSetupStep() == 2)
                            Correo electrónico
                        @elseif($user->getSetupStep() == 3)
                            Nombre
                        @elseif($user->getSetupStep() == 4)
                            Normas
                        @elseif($user->getSetupStep() == 5)
                            Prueba escrita
                        @elseif($user->getSetupStep() == 6)
                            Enlace foro
                        @elseif($user->getSetupStep() == 7)
                            Entrevista
                        @else
                            ?
                        @endif
                            ({{ $user->getSetupStep() }}/{{$user->getSetupSteps()}})
                        <div class="progress">
                            <div class="determinate" style="width: {{ round(($user->getSetupStep() / $user->getSetupSteps()) * 100) }}%"></div>
                        </div>

                        @if($user->getSetupStep() == 0)
                            {{--<a href="" class="btn white blue-text">Marcar como juego comprado</a>--}}
                        @elseif($user->getSetupStep() == 5)
                            {{--Prueba escrita--}}
                        @elseif($user->getSetupStep() == 7)
                            @if(is_null($user->getInterviewExam()->interviewer))
                                <p>El usuario está a la espera de ser entrevistado.</p>
                                @permission('mod-interview')
                                    <form action="{{ route('mod-interview', $user->getInterviewExam()) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn white blue-text waves-effect">Empezar entrevista</button>
                                    </form>
                                @endpermission
                            @else
                                @if($user->getInterviewExam()->interviewer->is(Auth::user()))
                                <p>Estás entrevistándole.</p>
                                <a href="{{ route('mod-interview', $user->getInterviewExam()) }}" class="btn indigo white-text waves-effect">Continuar entrevista</a>
                                @else
                                <span><b>{{ $user->getInterviewExam()->interviewer->username }} le está entrevistando en este momento.</b></span>
                                @endif
                            @endif
                        @endif

                    </div>
                @endif
                <p><i class="mdi mdi-account-card-details"></i> Nombres</p>
                <div class="card-panel">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Obs.</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($user->names()->orderBy('created_at', 'desc')->get() as $name)
                                    <tr>
                                        <td>{{ $name->name }}</td>
                                        <td>
                                            @if($name->type == 'imported')
                                                <i class="mdi mdi-exit-to-app tiny tooltipped" data-tooltip="Importado"></i>
                                            @endif
                                            @if(!is_null($name->original_name) && $name->name != $name->original_name)
                                                <i class="mdi mdi-pencil tiny tooltipped" data-tooltip="{{ $name->original_name }} → {{ $name->name }}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($name->invalid)
                                                <span class="red-text">No válido</span>
                                            @else
                                                @if($name->needs_review)
                                                    Esperando revisión
                                                @else
                                                    @if(!is_null($name->end_at))
                                                        Fin {{ $name->end_at->diffForHumans() }}
                                                    @elseif(!is_null($name->active_at))
                                                        <b><i class="mdi mdi-check-circle tiny green-text"></i> Activo {{ $name->active_at->diffForHumans() }}</b>
                                                    @else
                                                        ?
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('names.show', $name)}}" class="btn-flat waves-effect"><i class="mdi mdi-eye"></i></a>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p><i class="mdi mdi-note-text"></i> Exámenes</p>
                <div class="card-panel">
                    @if($user->exams()->count() > 0)
                        <table class="highlight">
                            <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Últ. act.</th>
                                <th>Expira</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->exams()->latest()->get() as $exam)
                                <tr>
                                    <td>
                                        @if(!$exam->finished && $exam->end_at > \Carbon\Carbon::now())
                                            En proceso
                                        @endif
                                        @if(is_null($exam->passed) && ($exam->finished || $exam->end_at <= \Carbon\Carbon::now()))
                                            Esperando corrección ({{ $exam->answers()->whereNotNull('score')->count()}}/{{ $exam->answers()->count() }})
                                        @endif
                                        @if(!is_null($exam->passed) && !$exam->passed)
                                            <i class="mdi mdi-receipt red-text tiny"></i> Prueba escrita suspensa
                                        @endif
                                        @if(!is_null($exam->passed) && $exam->passed && is_null($exam->interview_passed))
                                            <i class="mdi mdi-alarm tiny"></i> Aprobado, esperando entrevista
                                        @endif
                                        @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && !$exam->interview_passed)
                                           <i class="mdi mdi-microphone-off red-text tiny"></i> Entrevista suspendida
                                        @endif
                                        @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && $exam->interview_passed)
                                            <b><i class="mdi mdi-check-circle green-text tiny"></i> Escrito y oral aprobados</b>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $exam->updated_at->setTimezone(Auth::user()->timezone)->format('d/m/y H:i') }}
                                    </td>
                                    <td>
                                        @if(!is_null($exam->passed) && (!is_null($exam->interview_passed) || !$exam->passed))
                                            -
                                        @else
                                            {{ $exam->expires_at->diffForHumans() }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('exams.show', $exam) }}" class="btn-flat waves-effect"><i class="mdi mdi-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @elseif(!$user->imported_exam_exempt)
                        <p>Ningún examen todavía.</p>
                    @endif
                    @if($user->imported_exam_exempt)
                        <b>El usuario está exento de hacer el examen.</b>
                    @endif
                </div>
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
<script src="/js/clipboard.min.js"></script>
<script type="text/x-template" id="template-reveal">
    <span>
        <span v-if="!loading && !error && !loaded">
            <a href="" @click.prevent="reveal()">Haz clic para revelar...</a>
        </span>
        <span v-if="loading">
            <a>Cargando<span class="ellipsis-anim"><span>.</span><span>.</span><span>.</span></span></a>
        </span>
        <span v-if="!loading && error">
            <small class="red-text">@{{ errorMessage }}</small>
        </span>
        <span v-if="!loading && !error && loaded">
            @{{ info }}
            <span v-if="info == ''"><i>No establecido.</i></span>
        </span>
    </span>
</script>
<script>
    Vue.component('reveal', {
        template: '#template-reveal',
        props: {endpoint: {required: true}},
        data: function() {
            return {
                loading: false,
                loaded: false,
                error: false,
                info: null,
                errorMessage: "No se ha podido obtener la información.",
            }
        },
        methods: {
            reveal: function() {
                var vm = this;
                if(!confirm("Esta información es personal.\n¿Es necesario acceder? Tu acceso quedará registrado.")) {
                    return false;
                }
                vm.loading = true;
                axios.post(vm.endpoint)
                .then(function(response) {
                    vm.loading = false;
                    vm.error = false;
                    vm.info = response.data;
                    vm.loaded = true;
                }).catch(function(error) {
                    vm.loading = false;
                    vm.error = true;
                    if(error.response.status === 403) {
                        vm.errorMessage = "No tienes autorización para ver esta información."
                    }
                });
            }
        }
    });

    var app = new Vue({
        el: '#app',
        containers: ['reveal']
    });
</script>
@endsection
@section('head')
    <style>

        .ellipsis-anim span {
            opacity: 0;
            -webkit-animation: ellipsis-dot 1s infinite;
            animation: ellipsis-dot 1s infinite;
        }

        .ellipsis-anim span:nth-child(1) {
            -webkit-animation-delay: 0.0s;
            animation-delay: 0.0s;
        }
        .ellipsis-anim span:nth-child(2) {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s;
        }
        .ellipsis-anim span:nth-child(3) {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        @-webkit-keyframes ellipsis-dot {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        @keyframes ellipsis-dot {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
@endsection