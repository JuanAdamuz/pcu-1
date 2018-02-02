@extends('layouts.pcu')
@section('content')
    <div class="container" id="app">
        <br>
        <h5>Descarga WhiteList.txt</h5>
        <p>Descargar desde aquí el archivo de whitelist para el Battleye</p>
        <div class="row">
            <div class="col s12 m6">
                <div class="card-panel">
                    <a :disabled="loading" @click="loading = true" href="{{ route('whitelist-download') }}" class="btn blue waves-effect"><i class="mdi mdi-download left"></i> Descargar</a>
                    <br>
                    <small v-if="!loading">Se descargará un archivo llamado WhiteList.txt</small>
                    <small v-cloak v-if="loading">Generando y descargando WhiteList.txt<span class="ellipsis-anim"><span>.</span><span>.</span><span>.</span></span></small>
                </div>
                @if(Cache::has('whitelist-user-id'))
                    <p>Información sobre la whitelist</p>
                    <div class="card-panel">
                        <p>
                            <small>Gente en whitelist:</small>
                            <br>{{ Cache::get('whitelist-count') }} jugadores
                        </p>
                        <p>
                            <small>Última vez generada:</small>
                            <br>{{ Cache::get('whitelist-at')->setTimeZone(Auth::user()->timezone)->format('d/m/Y H:i') }} por {{ \App\User::find(Cache::get('whitelist-user-id'))->username }}
                        </p>
                    </div>
                @endif
            </div>
            <div class="col s12 m6">
                <div class="card-panel">
                    <b>Instrucciones</b>
                    <p>
                        1. Descargar el archivo y reemplazar el existente
                    </p>
                    <p>
                        2. Sustituir el archivo WhiteList.txt del servidor por el descargado
                    </p>
                    <p>
                        3. Reiniciar Battleye o lo que haya que hacer.
                        <br>
                    </p>
                </div>
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
            }
        });
    </script>
@endsection