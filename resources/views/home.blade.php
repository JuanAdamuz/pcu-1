@extends('layouts.pcu')

@section('title', 'Página principal')

@section('content')

<br>

<div class="container" id="app">
    <div class="row">
        @if(!config('pcu.enable_integration') || is_null($user->player))
            @include('home.newplayer')
        @else
            @include('home.player')
        @endif
        <div class="col l3 s12">
            <p>Enlaces rápidos</p>
            <div class="collection ">
                @if(config('pcu.pop_opened'))
                    <a href="{{ route('page', ['slug' => 'play']) }}" class="collection-item waves-effect green accent-4 white-text"><i class="mdi mdi-play"></i> Jugar</a>
                @endif
                <a href="{{ route('page', ['slug' => 'descargas']) }}" class="collection-item waves-effect light-blue-text"><i class="mdi mdi-download"></i> Descargas</a>
                <a href="{{ route('page', ['slug' => 'normas']) }}" class="collection-item waves-effect light-blue-text">Normas</a>
                @if(config('pcu.pop_opened'))
                    <a href="{{ route('page', ['slug' => 'inrol']) }}" class="collection-item waves-effect light-blue-text">Dentro de rol</a>
                @endif

            </div>
        </div>

        <div class="col s12 l9">
            <p>Últimas novedades</p>
            @foreach($posts as $post)
                <div class="card-panel">
                    @if($post->created_at->addDays(1) > \Carbon\Carbon::now())
                        <div class="chip red accent-3 white-text"><i class="mdi mdi-alert-decagram chipicon"></i> Nuevo</div>
                    @endif
                    <div class="flow-text" style="padding-top: 16px">{{ $post->title }}</div>
                    <div class="right">
                    </div>
                    @if($loop->first)
                        {% $post->body %}
                        <div class="divider" style="margin-top: 16px"></div>
                        <small class="right">Por {{ $post->user->username }}, {{ $post->created_at->diffForHumans() }}</small>
                    @else
                        {% str_limit($post->body, 200, $end = '...') %}
                        <div class="divider" style="margin-top: 16px"></div>
                        {{--<small><a href="">Leer noticia completa</a></small>--}}
                        <small class="right">Por {{ $post->user->username }}, {{ $post->created_at->diffForHumans() }}</small>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                opening: moment.tz(new Date('{{ $opening }}'), '{{ config('app.timezone') }}'),
                timezone: '{{ Auth::user()->timezone }}'
            }
        });
    </script>
@endsection