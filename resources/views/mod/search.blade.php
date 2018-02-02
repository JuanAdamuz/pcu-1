@extends('layouts.pcu')

@section('title', 'Búsqueda de usuarios')

@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <h5>Búsqueda</h5>
        <br>
        @include('common.errors')
        <form action="">
            <input name="q" type="text" placeholder="Escribe un nombre, SteamID, GUID y pulsa intro" value="{{ request()->input('q') }}" autofocus>
        </form>
        <br>
        <p><i class="material-icons tiny">list</i> Resultados ({{ $results->count() }})</p>
        <br>
        @if($results->count() == 0)
            <p><b>Ningún resultado.</b></p>
            <p>Prueba a repetir la búsqueda con otros parámetros.</p>
        @endif
        @foreach($results as $user)
            <a href="{{ route('mod-user', $user) }}">
                <div class="card-panel hoverable black-text">
                    <div class="row">
                        <div class="col s12 m6">
                            <b>@if($user->hasFinishedSetup()) <i class="mdi mdi-approval green-text tiny"></i> @endif {{ $user->username }}</b>
                            <br>{{ $user->steamid }} @if($user->imported) <i class="material-icons tiny tooltipped" data-tooltip="Importado">input</i> @endif
                        </div>
                        <div class="col s12 m6">
                        @if(!$user->hasFinishedSetup())
                                Estado: {{ $user->getSetupStep() }}/{{$user->getSetupSteps()}}
                                (@if($user->getSetupStep() == 0)
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
                                @endif{{-- Si no ponto esto no va. Gracias Blade. --}})
                                <div class="progress">
                                    <div class="determinate" style="width: {{ round(($user->getSetupStep() / $user->getSetupSteps()) * 100) }}%"></div>
                                </div>
                        @else
                                @if($user->hasFinishedSetup()) <i class="mdi mdi-approval green-text tiny"></i> <b>Certificado</b> @endif
                        @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        {{ $results->links() }}
    </div>
@endsection