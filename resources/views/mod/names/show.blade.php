@extends('layouts.pcu')
@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <small><a href="{{ route('mod-user', $name->user) }}"><< perfil de {{ $name->user->username }}</a></small>
        <h5>{{ $name->name }} <small>(NOMBRE)</small> </h5>
        <div class="row">
            <div class="col s12 m6">
                <p>Información</p>
                <div class="card-panel">
                    <p>
                        <small>Nombre:</small>
                        <br>{{ $name->name }}
                    </p>
                    @if(!is_null($name->original_name))
                        <p>
                            <small>Nombre sin corregir:</small>
                            <br>{{ $name->original_name }}
                        </p>
                    @endif
                    <p>
                        <small>Tipo:</small>
                        <br><code>{{ $name->type }}</code>
                    </p>
                    <p>
                        <small>Creado:</small>
                        <br>{{ $name->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Última actualización:</small>
                        <br>{{ $name->updated_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <small>Acciones:</small>
                        <br>
                        @if($name->invalid || !is_null($name->end_at) || $name->needs_review)
                            @permission('mod-name-accept')
                            <form onsubmit="return confirm('¿Activar nombre?')" action="{{ route('mod-user-name-enable', $name->user) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="nameid" value="{{ $name->id }}">
                                <button type="submit" class="btn white waves-effect green-text"><i class="material-icons left">check_circle</i> Activar</button>
                            </form>
                            @endpermission
                        @endif
                        @if(isset($name->active_at) && is_null($name->end_at))
                            @permission('mod-name-reject')
                            <form onsubmit="return confirm('¿Desactivar nombre?')" action="{{ route('mod-user-name-disable', $name->user) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="nameid" value="{{ $name->id }}">
                                <button type="submit" class="btn white waves-effect red-text"><i class="material-icons left">block</i> Desactivar</button>
                            </form>
                            @endpermission
                        @endif
                    </p>
                </div>

            </div>
            <div class="col s12 m6">
                <p>Estado</p>
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12">
                            <p>
                                <small>Estado:</small>
                                <br>
                                @if($name->needs_review)
                                    Esperando revisiones
                                @else
                                    @if($name->invalid)
                                        <span class="red-text">No válido</span>
                                    @elseif(!is_null($name->end_at))
                                        Se dejó de usar el {{ $name->end_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                                    @elseif(!is_null($name->active_at))
                                        <i class="mdi mdi-check-circle green-text"></i> <b>Activo</b> desde {{ $name->active_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                                    @else
                                        ¿?
                                    @endif
                                @endif
                            </p>
                        </div>
                        @permission('mod-name-reviewers')
                        <br>
                        <div class="col s12">
                            <small>Revisiones:</small>
                        </div>
                        @foreach($name->reviews as $review)
                            <div class="col s4">
                                <div class="card-panel @if($review->score == 100) green lighten-4 @endif @if($review->score < 100 && $review->score > 0) yellow lighten-4 @endif @if($review->score == 0) orange lighten-4 @endif">
                                    {{ $review->score }}%
                                    <br><small>{{ $review->user->username }}</small>
                                    <br><small>{{ $review->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                        @if($name->reviews->count() == 0)
                            <div class="col s12">
                                <p>Ninguna revisión.</p>
                            </div>
                        @else
                            <div class="col s12">
                                Puntuación: {{ $name->reviews->average('score') }}%
                                <br>
                                <small>{{ $name->reviews->count() }}/3 revisiones</small>
                            </div>
                        @endif
                        @endpermission
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection