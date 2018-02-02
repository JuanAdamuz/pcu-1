@extends('layouts.pcu')
@section('title', 'Lista de páginas')
@section('content')
    @include('admin.menu')
    <div class="container">
        <br>
        <div class="row">
            <div class="col s6">
                <h5>Páginas</h5>
            </div>
            <div class="col s6">
                <a href="{{ route('admin-pages-create') }}" class="right btn-flat waves-effect"><i class="material-icons left">add</i> Crear página</a>
            </div>
            <div class="col s12">
                @foreach($pages as $page)
                    <a href="">
                        <div class="card-panel black-text hoverable">
                            <div class="row">
                                <div class="col s6">
                                    <b>{{ $page->title }}</b>
                                    <br><code>{{ $page->slug }}</code>
                                </div>
                                <div class="col s6">
                                    Últ. edición: {{ $page->updated_at->diffForHumans() }}
                                    <br>Creado: {{ $page->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection