@extends('layouts.pcu')

@section('title', 'Lista de permisos')

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>Permisos</h5>
                <p>Lista de permisos asignables. No se pueden editar ni eliminar.</p>
            </div>
            <div class="col s12">
                <div class="card-panel">
                    <table class="highlight responsive-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->display_name }}</td>
                                <td>{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                        @if($permission->count() == 0)
                            <p><b>No hay ningún permiso.</b></p>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection