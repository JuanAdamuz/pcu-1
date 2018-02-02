@extends('layouts.pcu')

@section('title', 'Tu cuenta')

@section('content')
    <br>
    <div class="container">
        <h5>{{ $user->username }}</h5>

        <nav class=" nav-extended black white-text">
            <div class="nav-wrapper">
                <ul class="left hide-on-med-and-down">
                    <li><a class="waves-effect" href="#"><i class="material-icons left">account_circle</i> Tu cuenta</a></li>
                </ul>
                <div class="nav-content">
                    <ul class="tabs tabs-transparent">
                        <li class="tab"><a class="active" href="#data">Datos</a></li>
                        <li class="tab">
                            <a href="#whitelist">
                                Certificación
                                @if(!$user->hasFinishedSetup())
                                    <span class="white black-text new badge" data-badge-caption="">{{ $user->getSetupStep() }}/{{$user->getSetupSteps()}}</span>
                                @endif
                            </a>
                        </li>
                        <li class="tab"><a href="#names">Nombres</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        @if(!$user->hasFinishedSetup())
            <div class="col s12">
                <div class="card-panel">
                    <i class="mdi mdi-alert-octagram"></i> Todavía no has completado el proceso de certificación. <a href="{{ route('setup-info') }}">Haz clic aquí para ir a completarlo →</a>
                </div>
            </div>
        @endif

        <div id="data" class="col s12">
            <div class="row">
                <div class="col s12 l6">
                    <p>Datos</p>
                    <div class="card-panel">
                        <p>
                            <small>ID de Steam:</small>
                            <br><span>{{ $user->steamid }}</span>
                        </p>
                        <p>
                            <small>GUID de BattlEye:</small>
                            <br><span>{{ $user->guid }}</span>
                        </p>
                        <p>
                            <small>País:</small>
                            <br><span>

                            @if(!is_null($user->country))
                                    @php
                                        $country = Countries::where('cca2', $user->country)->first();
                                        $countryName = "?";
                                        try {
                                            $countryName = $country->translations->spa->common;
                                        } catch(\Exception $e) {
                                            // :)
                                        }
                                    @endphp
                                    {!! $country->flag['flag-icon'] !!}
                                    {{ $countryName }}
                                @else
                                    No indicado
                                @endif
                        </span>
                        </p>
                        <p>
                            <small>Zona horaria:</small>
                            <br><span>{{ $user->timezone }} <small>({{ Carbon::now()->setTimezone($user->timezone)->format('d/m/Y H:i') }})</small></span>
                        </p>
                        <p>
                            <small>Enlace al foro:</small>
                            <br><span>{{ is_null($user->ipb_token) ? "No enlazado" : "Cuenta enlazada" }}</span>
                        </p>
                    </div>
                    @permission('user-abilities-view')
                    <p>Tus permisos</p>
                    <div class="card-panel">
                        <p>
                            <small>Grupos:</small>
                        <ul>
                            @foreach($user->roles as $role)
                                <li>{{ $role->display_name }}</li>
                            @endforeach
                            @if($user->roles->count() == 0)
                                <li>No perteneces a ningún grupo.</li>
                            @endif
                        </ul>
                        </p>
                        <p>
                            <small>Permisos individuales:</small><br>
                        <ul>
                            @foreach($user->permissions as $permission)
                                <li>{{ $permission->display_name }}</li>
                            @endforeach
                            @if($user->permissions->count() == 0)
                                <li>No tienes ningún permiso individual asignado.</li>
                            @else
                                <br>
                                <li><small>Por favor, no comentes con otros miembros del staff tus permisos individuales.</small></li>
                            @endif
                        </ul>
                        </p>
                    </div>
                    @endpermission
                </div>
                <div class="col s12 l6">
                    <p>Notificaciones por correo electrónico</p>
                    <div class="card-panel">
                        @if($user->email_enabled)
                            <p>
                                <small>Dirección:</small>
                                <br><span>{{ $user->email or "?" }}</span>
                            </p>
                            <p>
                                <small>Verificado:</small>
                                <br><span>{{ $user->email_verified ? "Sí, " . $user->email_verified_at->diffForHumans() : "No" }}</span>
                            </p>
                        @else
                            <p>No has activado el correo electrónico.</p>
                            @if($user->canEnableEmail())
                                <form action="{{ route('account-resetemail') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn blue waves-effect">Activar notificaciones por correo</button>
                                </form>
                            @else
                                <a disabled class="btn">Activar notificaciones por correo</a>
                                @if($user->email_prevent)
                                    <p><small>No puedes activar el correo electrónico. <br>Ponte en contacto con un administrador para más información.</small></p>
                                @else
                                    <p><small>En este momento no puedes activar las notificaciones. Inténtalo de nuevo más tarde.</small></p>
                                @endif
                            @endif
                        @endif
                    </div>
                    @if($user->email_verified && $user->email_enabled)
                        <form action="{{ route('account-resetemail') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="disable" value="true">
                            <button type="submit" class="btn-flat white red-text waves-effect">Desactivar notificaciones por correo</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div id="whitelist" class="col s12">
            <div class="row">
                <div class="col s12">
                    <p><i class="mdi mdi-passport tiny"></i> Estado certificación</p>
                    @if($user->hasFinishedSetup())
                        <div class="card-panel">
                            <p><b><i class="mdi mdi-approval green-text tiny"></i> Cuenta certificada</b></p>
                            @if($user->imported_exam_exempt)
                                <p>Estás exento de hacer el examen.</p>
                            @endif
                        </div>
                        @if(is_null($user->whitelist_at))
                            <p>Estado whitelist</p>
                            <div class="card-panel">
                                <b>Todavía no puedes acceder al servidor. </b>
                                <p>Estamos añadiéndote a la lista de jugadores autorizados. Inténtalo en unos minutos.</p>
                            </div>
                        @endif
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
                        </div>
                        @if($user->imported && !$user->imported_exam_exempt)
                            <div class="card-panel">
                                <b>Repetición del proceso de whitelist</b>
                                <p>Debido a unas circunstancias en tu cuenta, tienes que repetir el proceso de certificación.</p>
                                @if(isset($user->imported_exam_message))
                                    <p>
                                        @if($user->imported_exam_message == '72h')
                                            <b>En tu caso</b>, tienes una sanción con una duración igual o mayor a 72 horas.
                                        @elseif($user->imported_exam_message == 'Reiterado')
                                            <b>En tu caso</b>, tienes más de una sanción por el mismo motivo. Es decir: cometiste una infracción de forma reiterada.
                                        @elseif($user->imported_exam_message == 'Perma')
                                            <b>En tu caso</b>, vienes de haber sido sancionado permanentemente.
                                        @else
                                            <b>En tu caso</b>, este es el motivo: "{{ $user->imported_exam_message }}"
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endif
                        <p>Tus intentos</p>
                        @foreach($user->exams as $exam)
                            <div class="card-panel">
                                <b>
                                    @if(!$exam->finished && $exam->end_at > \Carbon\Carbon::now())
                                        En proceso
                                    @endif
                                    @if(is_null($exam->passed) && ($exam->finished || $exam->end_at <= \Carbon\Carbon::now()))
                                        Esperando corrección
                                    @endif
                                    @if(!is_null($exam->passed) && !$exam->passed)
                                        <i class="mdi mdi-receipt red-text tiny"></i> Prueba escrita no superada
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && is_null($exam->interview_passed))
                                        <i class="mdi mdi-alarm tiny"></i> Aprobado, esperando entrevista
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && !$exam->interview_passed)
                                        <i class="mdi mdi-microphone-off red-text tiny"></i> Entrevista no superada
                                    @endif
                                    @if(!is_null($exam->passed) && $exam->passed && !is_null($exam->interview_passed) && $exam->interview_passed)
                                        <i class="mdi mdi-check-circle green-text tiny"></i> Escrito y oral aprobados
                                    @endif
                                </b>
                                <br><small>{{ $exam->updated_at->diffForHumans() }}</small>
                            </div>
                            @if(!$user->hasFinishedSetup())
                                <small>Intentos restantes: {{ $user->getExamTriesRemaining() }}/3</small>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div id="names" class="col s12">
            @if(!is_null($user->name))
                <div class="card-panel">
                    <p><b>Tienes un alias establecido por un administrador.</b></p>
                    <p>Tu alias es: "{{ $user->name }}"</p>
                    <p><small>Dependiendo de tu situación, podrías tener que usar otro nombre para jugar. Consulta a un administrador.</small></p>
                </div>
            @endif
            @if($user->names->count() == 0)
            <div class="card-panel">
                <b>Todavía no tienes un nombre elegido.</b>
            </div>
            @endif
                <p>Nombres</p>
            @foreach($user->names->sortByDesc('created_at') as $name)
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12 m6">
                                <b>"{{ $name->name }}"</b>
                                <br>
                                @if($name->needs_review)
                                    <span><i class="mdi mdi-clock tiny"></i> A la espera de ser revisado.</span>
                                @else
                                    @if(! $name->invalid)
                                        @if(is_null($name->end_at))
                                            <b><span><i class="mdi mdi-check-circle tiny green-text"></i> Activo desde {{ $name->active_at->diffForHumans() }}</span></b>
                                        @endif
                                    @else
                                        <i class="mdi mdi-block-helper tiny red-text"></i> Nombre no válido
                                    @endif
                                @endif
                        </div>
                        <div class="col s12 m6">
                            Solicitado {{ $name->created_at->diffForHumans() }}
                            <br>Tipo:
                            @if($name->type == 'imported')
                                importado de una versión anterior
                            @elseif($name->type == 'setup')
                                normal
                            @elseif($name->type == 'change')
                                cambio de nombre
                            @else
                                ?
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @if($user->name_changes_remaining > 0)
                <div class="card-panel">
                    <b>Cambio de nombre</b>
                    <p>Tienes permitido cambiarte el nombre {{ $user->name_changes_remaining }} {{ $user->name_changes_remaining == 1 ? "vez" : "veces" }}.</p>
                    <small>Ten en cuenta que no podrás volver a ponerte el nombre que tienes ahora una vez lo cambies.</small>
                    <br>
                    <small>El nuevo nombre tendrá que ser revisado. Te notificaremos cuando ya sea oficial.</small>
                    <br>
                    <br>
                    @if(!$user->hasFinishedSetup())
                        <a disabled class="btn blue waves-effect"><i class="material-icons left">mode_edit</i> Cambiar el nombre</a>
                        <br><small>Antes de cambiar tu nombre, necesitamos unos datos sobre ti. <a href="{{ route('setup-info') }}">Completar</a></small>
                    @else
                        <a href="{{ route('account-namechange') }}" class="btn blue waves-effect"><i class="material-icons left">mode_edit</i> Cambiar el nombre</a>
                    @endif
                    <p>
                        <small>La opción de cambiarte el nombre es algo fuera de lo común. Solo podrás cambiarlo {{ $user->name_changes_remaining }} {{ $user->name_changes_remaining == 1 ? "vez" : "veces" }}.</small>
                        <br>
                        @if(!is_null($user->name_changes_reason)) <small>En tu caso, el motivo para poder cambiarte el nombre es el siguiente: {{ $user->name_changes_reason or "(no definido)" }}</small> @endif
                    </p>
                </div>
            @else
                <p><small>No tienes permitido cambiarte el nombre en este momento.</small></p>
            @endif
        </div>
        <div id="test4" class="col s12">

        </div>


    </div>
@endsection
@section('head')
    {{--<link rel="stylesheet" href="/css/flag-icon.min.css">--}}
@endsection