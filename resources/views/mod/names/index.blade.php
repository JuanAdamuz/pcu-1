@extends('layouts.pcu')
@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <h5>Búsqueda de nombres</h5>
        <br>
        <form action="">
            <input name="q" type="text" placeholder="Escribe un nombre, SteamID, GUID y pulsa intro" value="{{ request()->input('q') }}" autofocus>
            @foreach(\Illuminate\Support\Facades\Input::except('q') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>

        <div class="chip dropdown-button-extend clickable @if(request()->has('type')) black white-text @endif" data-activates='dropdown-type'>
            Tipo: @if(request()->has('type')) <b>{{ request()->input('type') }}</b> @endif
            <i class="chipicon material-icons">list</i>
        </div>
        <!-- Dropdown Structure -->
        <ul id='dropdown-type' class='dropdown-content'>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Da igual</a></li>
            <li class="divider"></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'imported']) }}" class="waves-effect">Importado</a></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'change']) }}" class="waves-effect">Cambio</a></li>
            <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('type') +  ['type' => 'setup']) }}" class="waves-effect">Inicial</a></li>
        </ul>

        <br>
        <p><i class="material-icons tiny">list</i> Resultados ({{ $results->total() }})</p>
        @if($results->total() == 0)
            <br>
            <p><b>Ningún resultado.</b></p>
            <p>Prueba a repetir la búsqueda con otros parámetros.</p>
        @endif
        @foreach($results as $name)
            <a href="{{ route('names.show', $name) }}">
                <div class="card-panel hoverable black-text">
                    <div class="row">
                        <div class="col s12 m6">
                            @if($name->needs_review)
                                <i class="mdi mdi-dots-horizontal"></i>
                            @else
                                @if($name->invalid)
                                    <i class="mdi mdi-block-helper red-text"></i>
                                @elseif(!is_null($name->end_at))
                                    <i class="mdi mdi-clock-end"></i>
                                @elseif(!is_null($name->active_at))
                                    <i class="mdi mdi-check-circle green-text"></i>
                                @else
                                    ¿?
                                @endif
                            @endif
                            <b>{{$name->name}}</b>
                            @if($name->name != $name->user->username)
                                <small>({{ $name->user->username }})</small>
                            @elseif($name->type == 'change')
                                <small>(<i class="mdi mdi-clock"></i> {{ $name->user->names()->whereNotNull('end_at')->latest()->first()->name }})</small>
                            @endif
                        </div>
                        <div class="col s12 m6">
                                <div class="chip @if(request()->has('type')) black white-text @endif" >
                                    @if($name->type == 'imported')
                                        Importado
                                    @elseif($name->type == 'change')
                                        Cambio
                                    @elseif($name->type == 'setup')
                                        Inicial
                                    @else
                                        <code>{{ $name->type or "?" }}</code>
                                    @endif
                                    <i class="chipicon material-icons">list</i>
                                </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        {{ $results->appends(\Illuminate\Support\Facades\Input::except('page'))->links() }}
    </div>
@endsection