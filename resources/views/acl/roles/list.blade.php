@extends('layouts.pcu')

@section('title', 'Grupos')

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h5>Grupos</h5>
            </div>
            <div class="col s6">
                <a href="{{ route('acl-roles-new') }}" class="btn-flat waves-effect right"><i class="material-icons left">add</i> Crear grupo</a>
            </div>
            <div class="col s12">
                <div class="card-panel">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Integrantes</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->users()->count() }}</td>
                                <td><a href="{{ route('acl-roles-edit', $role) }}" class="btn-flat"><i class="material-icons">mode_edit</i></a></td>
                            </tr>
                        @endforeach
                        @if($roles->count() == 0)
                            <p><b>No hay ningún grupo.</b> Crea alguno usando el botón de arriba.</p>
                            <p>Al no haber grupos, todos los usuarios tendrán los permisos por defecto.</p>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection