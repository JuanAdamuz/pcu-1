@extends('layouts.pcu')
@section('content')
    @include('inrol.justicia.menu')
    <div class="container">
        <br>
        <h5>Búsqueda de personas físicas</h5>
        <br>
        @include('common.errors')
        <form action="" method="GET">
            <input name="q" value="{{ request()->input('q') }}" type="text" placeholder="Introduzca DNI o nombre conocido" required>
        </form>
        <br>
        @if(is_null(request()->input('q')))
        @else
            <p><i class="material-icons tiny">list</i> Resultados ({{ $results->count() }})</p>
            <br>
            @if($results->count() == 0)
                <p><b>Ningún resultado.</b></p>
                <p>Prueba a repetir la búsqueda con otros parámetros.</p>
            @else
                @foreach($results as $player)
                    <div class="card-panel">
                        <b>{{ $player->name }}</b>
                        <br>{{ $player->dni }}
                    </div>
                @endforeach
            @endif
        @endif

    </div>
@endsection