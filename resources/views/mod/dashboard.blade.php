@extends('layouts.pcu')

@section('title', 'Panel del moderador')

@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <h5>Panel del moderador</h5>
        <br>
        @permission(['mod-search', 'mod-interview'])
            <div class="row">
                <div class="col s12">
                    <i class="material-icons tiny">search</i> Búsqueda de usuarios
                </div>
                <div class="col s12">
                    <br>
                    <form action="{{ route('mod-search') }}">
                        <input name="q" type="text" placeholder="Introduce un nombre, SteamID o GUID y pulsa intro" autofocus>
                    </form>
                </div>
            </div>
        @endpermission
        @permission(['mod-review-answers', 'mod-review-names'])
            <div class="row">
                <div class="col s12">
                    <i class="material-icons tiny">list</i> A revisar
                </div>
                <div class="col s12">
                    <div class="card-panel indigo white-text">
                        @php
                        $toReview = array();
                        if(Auth::user()->hasPermission('mod-review-answers')) {
                            $toReview[] = "Respuestas";
                        }
                        if(Auth::user()->hasPermission('mod-review-names')) {
                            $toReview[] = "Nombres";
                        }
                        @endphp
                        @foreach($toReview as $item)
                            {{ $item }}@if(!$loop->last), @endif
                        @endforeach
                        @if($count > 0)
                            <h5>{{ $count }}</h5>
                            <a href="{{ route('mod-review') }}" class="btn white indigo-text waves-effect" style="margin-top: 8px">Revisar</a>
                        @else
                            <h5 class="light">0 pendientes</h5>
                            <a href="{{ route('mod-review') }}" class="btn indigo white-text waves-effect" style="margin-top: 8px">Revisar</a>
                        @endif
                    </div>
                </div>
            </div>
        @endpermission
        @permission(['mod-supervise-answers'])
        <div class="row">
            <div class="col s12">
                <i class="material-icons tiny">supervisor_account</i> A supervisar
            </div>
            <div class="col s12">
                <div class="card-panel white indigo-text">
                    @php
                        $toReview = array();
                        if(Auth::user()->hasPermission('mod-supervise-answers')) {
                            $toReview[] = "Respuestas";
                        }
                    @endphp
                    @foreach($toReview as $item)
                        {{ $item }}@if(!$loop->last), @endif
                    @endforeach
                    @if($supervisionCount > 0)
                        <h5>{{ $supervisionCount }}</h5>
                        <a href="{{ route('mod-supervise') }}" class="btn indigo white-text-text waves-effect" style="margin-top: 8px">Supervisar</a>
                    @else
                        <h5 class="light">0 pendientes</h5>
                        <a href="{{ route('mod-supervise') }}" class="btn indigo white-text-text waves-effect" style="margin-top: 8px">Supervisar</a>
                    @endif
                </div>
            </div>
        </div>
        @endpermission
    </div>
@endsection